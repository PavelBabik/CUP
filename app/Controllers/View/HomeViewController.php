<?php

namespace App\Controllers\View;

use App\Controllers\ParserController;

class HomeViewController extends BaseViewController
{
    public function index()
    {
        $ParserController = new ParserController();
        $ParserController->start();
    }

    public function test()
    {
        prp($_COOKIE);
        prp($_POST);
    }
}