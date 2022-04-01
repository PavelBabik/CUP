<?php

namespace App\Controllers;

use App\Classes\TemporaryAccessLink;
use App\Classes\Workers;
use App\Entities\WorkerEntity;
use App\Models\WorkersModel;
use App\Types\DBWhereParamsType;
use App\Types\ResponseType;
use CodeIgniter\RESTful\ResourceController;


class WorkersController extends ResourceController
{
    private $WorkersModel;

    public function __construct()
    {
        $this->WorkersModel = new WorkersModel();
    }

    public function getUserDataByUsernameOrEmail($username)
    {
        $data = $this->WorkersModel
            ->where('username', $username)
            ->orWhere('email', $username)
            ->find()[0];

        return $data;
    }

    /**
     * @param array $payload
     * @return int
     *
     * Codes:
     * 1 - Success
     * 2 - Database error
     * 3 - Email/login is busy
     */
    public function createWorker(array $payload): int
    {
        $Workers = new Workers();

        return $Workers->create($payload);
    }

    /**
     * @param array $payload
     * @return int
     *
     * Codes:
     * 1 - Success
     * 2 - Database error
     * 3 - Email/login is busy
     */
    public function createAccount(array $payload): int
    {
        $Workers = new Workers();
        $payload['is_account'] = true;
        $payload['status'] = WorkerEntity::STATUS_ACTIVE;
        $payload['role'] = WorkerEntity::ROLE_CHAT_OPERATOR;

        return $Workers->create($payload);
    }

    /**
     * @param int $id
     * @return WorkerEntity|null
     */
    public function getDataByUserID(int $id): WorkerEntity|null
    {
        $Workers = new Workers();
        $data = $Workers->findByPrimary($id);

        return $data;
    }

    /**
     * @param array $workerData
     * @return ResponseType
     *
     * Codes:
     * 1 - success
     * 2 - Email/login is busy
     * 3 - Empty fields
     * 4 - Database error
     * 5 - Unknown error
     */
    public function registration(array $workerData): int
    {
        $Workers = new Workers();

        $workerData['username'] = $workerData['email'];

        $check = check_required_fields([
            'bank_card', 'cardholder', 'city',
            'email', 'name', 'password',
            'phone', 'role'
        ], $workerData);

        if (!$check['status']) {
            return 3;
        }

        if (
            $workerData['bank_card'] == '' ||
            $workerData['cardholder'] == '' ||
            $workerData['city'] == '' ||
            $workerData['email'] == '' ||
            $workerData['name'] == '' ||
            $workerData['password'] == '' ||
            $workerData['phone'] == '' ||
            $workerData['role'] == ''
        ) {
            return 3;
        }

        $saveStatus = $Workers->create($workerData);

        switch ($saveStatus) {
            case 1:
                return 1; //Success
            case 2:
                return 4;
            case 3:
                return 2;
            default:
                return 5;
        }
    }

    public function getDeletedList(): array
    {
        $Workers = new Workers();
        $data = $Workers->findAll(new DBWhereParamsType([
            'where' => [
                'status' => WorkerEntity::STATUS_DELETED,
            ],
            'ignore_deleted' => true,
        ]));

        return $data;
    }

    public function getNewList(): array
    {
        $Workers = new Workers();
        $data = $Workers->findAll(new DBWhereParamsType([
            'where' => [
                'status' => WorkerEntity::STATUS_NEW,
            ]
        ]));

        return $data;
    }

    public function getManagersList(): array
    {
        $Workers = new Workers();
        $data = $Workers->findAll(new DBWhereParamsType([
            'where' => [
                'role' => [
                    WorkerEntity::ROLE_CHECK_SUPERVISOR,
                    WorkerEntity::ROLE_COPYWRITER,
                    WorkerEntity::ROLE_CONTENT_MANAGER,
                    WorkerEntity::ROLE_CONTENT_ADMIN,
                    WorkerEntity::ROLE_ADMIN,
                ]
            ]
        ]));

        return $data;
    }

    public function getOperatorsList(): array
    {
        $Workers = new Workers();
        $data = $Workers->findAll(new DBWhereParamsType([
            'where' => [
                'role' => [
                    WorkerEntity::ROLE_CHAT_OPERATOR,
                    WorkerEntity::ROLE_MAIL_OPERATOR,
                ]
            ]
        ]));

        return $data;
    }

    public function getAdminsList(): array
    {
        $Workers = new Workers();
        $data = $Workers->findAll(new DBWhereParamsType([
            'where' => [
                'role' => [
                    WorkerEntity::ROLE_CHECK_SUPERVISOR,
                ],
                'status' => WorkerEntity::STATUS_ACTIVE
            ]
        ]));

        return $data;
    }

    /**
     * @param array $workerData
     * @return ResponseType
     *
     * Codes:
     * 1 - success
     * 2 - Email/login is busy
     * 3 - Empty fields
     * 4 - Database error
     * 5 - Unknown error
     * 6 - Unable to edit super admin
     */
    public function saveWorkerData(array $workerData): int
    {
        $WorkerEntity = new WorkerEntity();
        $WorkerEntity->load($workerData);

        $Workers = new Workers();
        $oldWorker = $Workers->findByPrimary($WorkerEntity->id);

        // Unable to edit super admin
        if ($oldWorker->role == WorkerEntity::ROLE_SUPER_ADMIN) {
            return 6;
        }

        $saveStatus = $Workers->edit($workerData);

        switch ($saveStatus) {
            case 1:
                return 1; //Success
            case 2:
                return 4;
            case 3:
                return 2;
            default:
                return 5;
        }
    }

    /**
     * @return array
     *
     * List of id's and names of all possible referrals
     */
    public function getReferralsList(): array
    {
        $Workers = new Workers();
        return $Workers->findAll(new DBWhereParamsType(['select' => 'id, name']));
    }

    public function getTemporaryAccessLink(int $worker_id): string
    {
        $TemporaryAccessLink = new TemporaryAccessLink();

        $link = generateRandomString(20);

        if ($TemporaryAccessLink->findByPrimary($link) !== null) { //If generated non-unique string
            $this->getTemporaryAccessLink($worker_id);
        } else {
            $TemporaryAccessLink->delete($worker_id, 'worker_id');

            $TemporaryAccessLink->create([
                'link' => $link,
                'worker_id' => $worker_id
            ]);
        }

        return $link;
    }


    ////////////// ACCOUNTS /////////////////

    public function getAccountsList(): array
    {
        $Workers = new Workers();
        return $Workers->findAll(new DBWhereParamsType([
            'where' => [
                'is_account' => true
            ]
        ])) ?? [];
    }
}
