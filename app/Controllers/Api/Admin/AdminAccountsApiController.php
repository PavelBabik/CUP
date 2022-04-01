<?php

namespace App\Controllers\Api\Admin;

use App\Classes\Papers;
use App\Controllers\WorkersController;
use App\Entities\PaperEntity;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\DBWhereParamsType;
use App\Types\ResponseType;
use CodeIgniter\HTTP\Response;

class AdminAccountsApiController extends BaseAdminApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'createAccount' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getAccountsList' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ]
    ];

    /**
     * @return ResponseType
     *
     * Codes:
     * (200) 1 - Success
     * (200) 2 - Email/login busy
     * (500) 1 - Database error
     */
    public function createAccount(): Response
    {
        $ResponseType = new ResponseType();
        $WorkersController = new WorkersController();

        $code = $WorkersController->createAccount($this->input['worker']);

        switch ($code) {
            case 1:
                $ResponseType->setHeaders(true, 200, 1);
                break;
            case 2:
                $ResponseType->setHeaders(true, 500, 1);
                break;
            case 3:
                $ResponseType->setHeaders(true, 200, 2);
                break;
        }

        return $this->respond($ResponseType);
    }

    public function getAccountsList(): Response
    {
        $ResponseType = new ResponseType();
        $WorkersController = new WorkersController();

        $data = $WorkersController->getAccountsList();

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    public function getAdminsList(): Response
    {
        $ResponseType = new ResponseType();
        $WorkersController = new WorkersController();

        $data = $WorkersController->getAdminsList();

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }
}

?>