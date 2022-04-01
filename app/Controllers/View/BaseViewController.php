<?php

namespace App\Controllers\View;

use App\Classes\Templates;
use App\Controllers\BaseController;

class BaseViewController extends BaseController
{
    protected $data = [];

    public function __construct()
    {
        $this->data['Templates'] = new Templates();
    }

}