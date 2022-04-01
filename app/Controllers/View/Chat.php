<?php

namespace App\Controllers\View;

use App\Controllers\Chat as ChatController;
use App\Models\ProfilesModel;

class Chat extends BaseViewController
{
    public function index()
    {
        $ChatController = new ChatController();
        $this->data = $ChatController->

        return view('Chat/index', $this->data);
    }
}