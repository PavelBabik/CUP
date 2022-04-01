<?php

namespace App\Models;

class ChatPhotoModel extends BaseModel
{
    public $table      = 'chat_photo';
    public $primaryKey = 'id';

    protected $returnType = '\App\Entities\ChatPhotoEntity';
    protected $allowedFields = [
        'id', 'profile_id', 'file_id',
        'file_name', 'category',
    ];
}