<?php

namespace App\Classes;

class Log
{
    const MAX_LOGFILE_SIZE = 10000000;

    public static function view(string $type, int|bool $page = false): array
    {
        if ($page === false) {
            $fileName = self::get_last_file_name(getenv('ROOT_DIR') . 'log/' . $type . '/');
        } else {
            $fileName = getenv('ROOT_DIR') . 'log/' . $type . '/log_' . $page . '.json';
        }

        $dir = scandir(getenv('ROOT_DIR') . 'log/' . $type . '/');
        unset($dir[0]);
        unset($dir[1]);

        $data = file_get_contents($fileName);
        $data = explode(PHP_EOL, $data);

        foreach ($data as &$datum) {
            $datum = json_decode($datum);
        }

        return [
            'data' => $data,
            'pages' => $dir
        ];
    }

    public static function websocket(array $data): void
    {
        $FILE_dir = '/var/www/back/log/websocket/';

        $data['datetime'] = date('Y-m-d H:i:s');

        $json = json_encode($data);

        self::put_to_file($FILE_dir, $json);
    }

    public static function curl(\CurlHandle $ch, string $res): void
    {
        $FILE_dir = getenv('ROOT_DIR') . 'log/curl/';
        $info = curl_getinfo($ch);
        $url = $info['url'];
        $http_code = $info['http_code'];

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($res, 0, $header_size);
        $body = substr($res, $header_size);

        $error = '';
        if ($http_code != 200) {
            $error = 'error';
        }

        if (json_decode($body) != null) {
            $body = json_decode($body);
        } else {
            $body = htmlspecialchars($body);
        }

        $data = [
            'date' => date('Y-m-d H:i:s'),
            'error' => $error,
            'url' => $url,
            'http_code' => $http_code,
            'info_string' => $info,
            'header' => $header,
            'body' => $body,
        ];
        $json = json_encode($data) . PHP_EOL;

        self::put_to_file($FILE_dir, $json);
    }

    private static function get_last_file_name($FILE_dir): string
    {
        $dir = scandir($FILE_dir);
        unset($dir[0]);
        unset($dir[1]);

        $i = sizeof($dir);

        return $FILE_dir . 'log_' . $i . '.json';
    }

    private static function put_to_file($FILE_dir, $content)
    {
        if (json_decode($content) == null) {
            die('put_to_file $content must to be valid JSON string!');
        }

        $dir = scandir($FILE_dir);
        unset($dir[0]);
        unset($dir[1]);

        $i = sizeof($dir);
        if ($i == 0) { // Create new file if clean directory
            $LAST_FILE_NAME = $FILE_dir . 'log_1.json';
            file_put_contents($LAST_FILE_NAME, $content);
        } else {
            $LAST_FILE_NAME = $FILE_dir . 'log_' . $i . '.json';
            $NEW_FILE_NAME = $FILE_dir . 'log_' . ++$i . '.json';
            $old_data = file_get_contents($LAST_FILE_NAME);

            if (strlen($old_data) > self::MAX_LOGFILE_SIZE) { // Max log file size
                file_put_contents($NEW_FILE_NAME, $content);
            } else {
                file_put_contents($LAST_FILE_NAME, $content . "\n" . $old_data);
            }
        }
    }
}