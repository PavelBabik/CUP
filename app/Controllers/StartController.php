<?php

namespace App\Controllers;

use App\Controllers\CurlController;

class StartController extends BaseController
{


    public function index()
    {
        $Curl = new CurlController();
        $Curl->getMessages();
        $result = $Curl->getMessages();
        var_dump($result.'SSS');
    }
}

