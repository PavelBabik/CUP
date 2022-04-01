<?php

namespace App\Models;

use App\Classes\ChatPhoto;
use App\Classes\ChatVideo;
use App\Entities\BaseEntity;
use App\Entities\CityEntity;
use App\Entities\FileEntity;
use App\Entities\MessageEntity;
use App\Entities\PaperEntity;
use App\Entities\ProfileEntity;
use App\Entities\WorkerEntity;
use CodeIgniter\Model;

class BaseModel extends Model
{
    public function findAction($id = null): array|null|CityEntity|MessageEntity|ProfileEntity|WorkerEntity|FileEntity|PaperEntity|BaseEntity|ChatPhoto|ChatVideo
    {
        return parent::find($id);
    }

    public function findAllAction(int $limit = 0, int $offset = 0): array|null|CityEntity|MessageEntity|ProfileEntity|WorkerEntity|FileEntity|PaperEntity|BaseEntity|ChatPhoto|ChatVideo
    {
        return parent::findAll($limit, $offset);
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
}