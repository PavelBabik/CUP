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

class AdminWorkersApiController extends BaseAdminApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'getReferralsList' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getTemporaryAccessLink' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getWorkersList' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getOneWorker' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'editWorker' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getWorkerPapers' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'deletePaper' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'addPaper' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'createWorker' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ]
    ];

    /**
     * @return array<WorkerEntity>
     *
     * Returns only name and ID of all workers
     */
    public function getReferralsList(): Response
    {
        $ResponseType = new ResponseType();

        $WorkersController = new WorkersController();
        $data = $WorkersController->getReferralsList();

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    /**
     * @param $worker_id
     * @return string
     *
     * Generate and return TemporaryAccess Link
     */
    public function getTemporaryAccessLink($worker_id): Response
    {
        $ResponseType = new ResponseType();

        $WorkersController = new WorkersController();
        $data = $WorkersController->getTemporaryAccessLink($worker_id);

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    /**
     * @param string $type
     * @return array<WorkerEntity>
     *
     * Codes:
     * (403) 1 - Bad request
     */
    public function getWorkersList(string $type): Response
    {
        $ResponseType = new ResponseType();

        $WorkersController = new WorkersController();

        switch ($type) {
            case 'deleted':
                $data = $WorkersController->getDeletedList();
                break;
            case 'new':
                $data = $WorkersController->getNewList();
                break;
            case 'managers':
                $data = $WorkersController->getManagersList();
                break;
            case 'operators':
                $data = $WorkersController->getOperatorsList();
                break;
            default:
                $ResponseType->setHeaders(false, 400, 1);
                return $this->respond($ResponseType);
        }

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    /**
     * @param $worker_id
     * @return WorkerEntity
     */
    public function getOneWorker($worker_id): Response
    {
        $ResponseType = new ResponseType();

        $WorkersController = new WorkersController();

        $data = $WorkersController->getDataByUserID($worker_id);

        if ($data === null) {
            $ResponseType->setHeaders(false, 404);
        } else {
            $data = $data->getDataAdminRules();

            // Unable to edit super admin
            if ($data['role'] == WorkerEntity::ROLE_SUPER_ADMIN) {
                $ResponseType->setHeaders(false, 403, 1);
                return $this->respond($ResponseType);
            }

            $ResponseType->setData($data);
        }

        return $this->respond($ResponseType);
    }

    /**
     * @return ResponseType
     */
    public function editWorker(): Response
    {
        $ResponseType = new ResponseType();

        $WorkersController = new WorkersController();

        $respond_code = $WorkersController->saveWorkerData($this->input['worker']);

        switch ($respond_code) {
            case 1:
                $ResponseType->setHeaders(true, 200, 1);
                break;
            case 2:
                $ResponseType->setHeaders(false, 200, 2);
                break;
            case 3:
                $ResponseType->setHeaders(false, 200, 3);
                break;
            case 4:
                $ResponseType->setHeaders(false, 500, 1);
                break;
            case 5:
                $ResponseType->setHeaders(false, 500, 2);
                break;
            case 6:
                $ResponseType->setHeaders(false, 403, 1); // Unable to edit super admin
                break;
            default:
                $ResponseType->setHeaders(false, 500, 2);
                break;
        }

        return $this->respond($ResponseType);
    }

    public function getWorkerPapers($worker_id): Response
    {
        $ResponseType = new ResponseType();

        $Papers = new Papers();

        $data = $Papers->findAll(new DBWhereParamsType([
            'where' => [
                'worker_id' => $worker_id
            ],
            'sort_direction' => 'ASC'
        ]));

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    public function deletePaper($paper_id): Response
    {
        $ResponseType = new ResponseType();

        $Papers = new Papers();
        $Papers->delete($paper_id);

        return $this->respond($ResponseType);
    }

    /**
     * @return ResponseType
     */
    public function addPaper(): Response
    {
        $ResponseType = new ResponseType();
        $Papers = new Papers();

        $PaperEntity = new PaperEntity();
        $PaperEntity->load($this->input['paper']);

        if ($PaperEntity->worker_id == 0) {
            $ResponseType->setHeaders(false, 200, 2);
            return $this->respond($ResponseType);
        }

        $Papers->create($this->input['paper']);

        return $this->respond($ResponseType);
    }

    /**
     * @return ResponseType
     *
     * Codes:
     * (200) 1 - Success
     * (200) 2 - Email/login busy
     * (500) 1 - Database error
     */
    public function createWorker(): Response
    {
        $ResponseType = new ResponseType();
        $WorkersController = new WorkersController();

        $code = $WorkersController->createWorker($this->input['worker']);

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
}

?>