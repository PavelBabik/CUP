<?php

namespace App\Controllers;

class CurlController extends BaseController
{
    public $hidden_params = [];

    public function request($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, '22780' . ":" . '95552178en5');
        curl_setopt($ch, CURLOPT_COOKIEJAR, getenv('ROOT_DIR') . 'tmp/Cookies/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, getenv('ROOT_DIR') . 'tmp/Cookies/cookie.txt');
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function auth()
    {
        $urlFirst = getenv('BASE_URL'). '/index/Login.aspx';
        $this->request($urlFirst);
        $urlSecond = getenv('API_URL').'/default';
        $this->request($urlSecond);
        print_r($this->request($urlFirst));
    }

    public function getMessages()
    {
        $this->auth();
//                $urlSecond = getenv('BASE_URL') ;
//        $this->request($urlSecond);
        $url = getenv('BASE_URL') . '/index/default.aspx';
        $dataRes = $this->request($url);
        $result = json_decode($dataRes);
        $girl = [];
        print_r($result);

        $newRes = $result[0];
//        foreach ($newRes->updates as $item) {
////            $member = $item->member;
////            $i = [];
////            $i['id'] = $member->id;
////            $i['name'] = $member->name;
////            $i['message'] = $item->text;
////            if (isset($item->cam)) {
////                $i['cam'] = true;
////            } else {
////                $i['cam'] = false;
////            }
////            $girl[] = $i;
//        }
        return $dataRes;
    }
}