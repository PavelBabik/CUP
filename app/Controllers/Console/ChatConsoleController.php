<?php

namespace App\Controllers\Console;

use App\Classes\Profile;
use App\Classes\Tokens;
use App\Classes\Workers;
use App\Controllers\ChatController;
use App\Models\TokensModel;
use App\Types\DBWhereParamsType;

class ChatConsoleController extends BaseConsoleController
{
    const TCP_SOCKET = 'tcp://127.0.0.1:1234';

    public function test()
    {
        $ChatController = new ChatController();
        $Profile = new Profile();

        $profilesList = $Profile->findAll(new DBWhereParamsType());
//        $profilesList[] = $Profile->findOne(6);

        $data = [];

        foreach ($profilesList as $profile) {
            $data[$profile->id] = $ChatController->updateMessages($profile->id);
        }

        $countOfNewMessages = 0;

        foreach ($data as $key => $datum) {
            if ($datum != null) {
                $countOfNewMessages += sizeof($datum);
            } else {
                unset($data[$key]);
            }
        }
        echo "\n\n" . $countOfNewMessages;

        $tokens = [
            '698efa3c025370817e72c3f66755d16727ffd0cb',
        ];
        $message = json_encode($data, JSON_FORCE_OBJECT);
//        $message = '{"412":{"101985664":{"0":{"id":139261491531,"profile_id":1956591,"man_id":101985664,"time":"2022-02-22 05:24:01","text":"Would you like to see me in uniform?\ud83e\udd13","in":false}},"8011553":{"0":{"id":139261491817,"profile_id":1956591,"man_id":8011553,"time":"2022-02-22 05:24:01","text":"What don&#39;t you like about me?\u2728\ud83c\udf6c\ud83d\ude09\ud83c\udf6c\u2728","in":false}}},"252":{"42943496":{"0":{"id":139261486896,"profile_id":2142046,"man_id":42943496,"time":"2022-02-22 05:23:54","text":"Would you rather be happy or rich?\u2764\ufe0f","in":false}}},"52":{"108303117":{"0":{"id":139261491612,"profile_id":1884919,"man_id":108303117,"time":"2022-02-22 05:24:01","text":"I wish to look into your eyes and forget about everything","in":false}}}}';

        $instance = stream_socket_client(self::TCP_SOCKET);
        fwrite($instance, json_encode(['tokens' => $tokens, 'message' => $message])  . "\n");
    }

    public function updateMessages()
    {
        $ChatController = new ChatController();
        $Profile = new Profile();
        $Workers = new Workers();
        $Tokens = new Tokens();

        $profilesList = $Profile->findAll(new DBWhereParamsType());

        foreach ($profilesList as $profile) {
            $data = [];

            if ($profile->access_id == 0) {
                continue;
            }

            $data[$profile->id] = $ChatController->updateMessages($profile->id);

            $Access = $Workers->findByPrimary($profile->access_id);

            $tokensList = $Tokens->findAll(new DBWhereParamsType([
                'select' => 'access_token',
                'where' => [
                    'user_id' => $Access->email
                ],
                'sort_row' => 'expires'
            ]));

            $tokens = [];
            foreach ($tokensList as $token) {
                $tokens[] = $token['access_token'];
            }

            $message = json_encode($data, JSON_FORCE_OBJECT);

            $instance = stream_socket_client(self::TCP_SOCKET);
            fwrite($instance, json_encode(['tokens' => $tokens, 'message' => $message])  . "\n");
        }
    }
}