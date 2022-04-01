<?php

namespace App\Controllers\Api;

use App\Classes\City;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\DBWhereParamsType;
use App\Types\ResponseType;
use CodeIgniter\HTTP\Response;

class CitiesApiController extends BaseApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'getAll' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
    ];

    public function getAll(): Response
    {
        $ResponseType = new ResponseType();
        $City = new City();
        $data = $City->findAll(new DBWhereParamsType(['sort_direction' => 'ASC']));

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }
}