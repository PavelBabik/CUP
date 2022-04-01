<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\StatsController;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use CodeIgniter\HTTP\Response;

class AdminStatsApiController extends BaseAdminApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'getCountersInUsersTabs' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
    ];

    public function getCountersInUsersTabs(): Response
    {
        $ResponseType = new ResponseType();

        $StatsController = new StatsController();
        $data = $StatsController->getCountersInUsersTabs();

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }
}