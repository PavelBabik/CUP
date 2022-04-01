<?php

namespace App\Controllers\View;

use App\Classes\Log;
use App\Entities\ProfileEntity;

class LogViewController extends BaseViewController
{
    public function __construct()
    {
        $this->setMiddleware();

        parent::__construct();
    }

    public function curl($page = -1)
    {
        if ($page == -1) {
            $data = Log::view('curl');
        } else {
            $data = Log::view('curl', $page);
        }

        $this->data['logsList'] = $data['data'];
        $this->data['pages'] = $data['pages'];

        return view('log/curl', $this->data);
    }

    public function websocket($page = -1)
    {
        if ($page == -1) {
            $data = Log::view('websocket');
        } else {
            $data = Log::view('websocket', $page);
        }

        $this->data['logsList'] = $data['data'];
        $this->data['pages'] = $data['pages'];

        return view('log/websocket', $this->data);
    }
}