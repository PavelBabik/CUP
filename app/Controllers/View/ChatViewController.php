<?php

namespace App\Controllers\View;

use App\Controllers\ChatController;

class ChatViewController extends BaseViewController
{
    public function index()
    {
        $ChatController = new ChatController();
        $this->data = $ChatController->getChatsIndex();

        return view('Chat/index', $this->data);
    }
}