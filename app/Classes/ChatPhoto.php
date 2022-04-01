<?php

namespace App\Classes;

use App\Interfaces\ClassInterface;
use App\Models\ChatPhotoModel;
use App\Models\ProfilesModel;

class ChatPhoto extends BaseClass implements ClassInterface
{
    public function __construct()
    {
        $this->Model = new ChatPhotoModel();
    }
}


