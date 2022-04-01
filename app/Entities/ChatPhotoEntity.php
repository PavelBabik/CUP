<?php

namespace App\Entities;

use App\Interfaces\EntityInterface;

class ChatPhotoEntity extends BaseEntity implements EntityInterface
{
    const CATEGORY_COMMON = 5;
    const CATEGORY_PRIVATE = 10;

    public int $id = 0;
    public int $profile_id = 0;
    public int $file_id = 0;
    public string|null $file_name = '';
    public int $category = self::CATEGORY_COMMON;
}