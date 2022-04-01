<?php

namespace App\Controllers;

use voku\helper\HtmlDomParser;

class ParserController extends BaseController
{
    const LOGIN_URL = 'https://www.top-dates.net/index/Login.aspx';


    private $postData = [
        'ctl00$ContentPlaceHolder1$txtBoxLogin' => '22780',
        'ctl00$ContentPlaceHolder1$txtBoxPWD' => '95552178en5',
    ];

    public function start()
    {
        $this->getCSRF();
        $this->auth();
    }

    private function getCSRF()
    {
        $dom = HtmlDomParser::file_get_html(self::LOGIN_URL);

        $this->postData['__CSRFTOKEN'] = $dom
            ->findOne('input[name="__CSRFTOKEN"]')
            ->getAttribute('value');
        $this->postData['__EVENTTARGET'] = $dom
            ->findOne('input[name="__EVENTTARGET"]')
            ->getAttribute('value');
        $this->postData['__EVENTARGUMENT'] = $dom
            ->findOne('input[name="__EVENTARGUMENT"]')
            ->getAttribute('value');
        $this->postData['__VIEWSTATE'] = $dom
            ->findOne('input[name="__VIEWSTATE"]')
            ->getAttribute('value');
    }

    private function auth()
    {
        function HandleHeaderLine( $curl, $header_line ) {
            echo "<br>YEAH: ".$header_line; // or do whatever
            return strlen($header_line);
        }

//        $ch = curl_init('http://pavel-parser/test');
        $ch = curl_init(self::LOGIN_URL);

        $cookie = 'csrftoken=' . $this->postData['__CSRFTOKEN'];

        $headers = [];
        $headers[] = 'Cookie: ' . $cookie;

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_USERPWD, '22780' . ":" . '95552178en5');
//        curl_setopt($ch, CURLOPT_COOKIEJAR, getenv('ROOT_DIR') . 'tmp/Cookies/cookie.txt');
//        curl_setopt($ch, CURLOPT_COOKIEFILE, getenv('ROOT_DIR') . 'tmp/Cookies/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
        $res = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($res, 0, $header_size);
        $body = substr($res, $header_size);

        var_dump($header);
//        var_dump($body);

        die;

//        prp($res);
        prp(curl_getinfo($ch));

        curl_close($ch);
        return $res;

    }


}