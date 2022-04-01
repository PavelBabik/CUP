<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\ChatVideoController;
use App\Entities\ChatVideoEntity;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use Codeigniter\HTTP\Response;

class AdminChatVideoApiController extends BaseAdminApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'getByProfileID' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'delete' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'create' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
    ];

    /**
     * @param $profile_id
     * @return array<ChatVideoEntity>
     */
    public function getByProfileID($profile_id): Response
    {
        $ResponseType = new ResponseType();

        $ChatVideoController = new ChatVideoController();
        $data = $ChatVideoController->getByProfileID($profile_id);

        if ($data == null) {
            $ResponseType->setData([]);
        }

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    /**
     * @param $ChatVideo_id
     * @return bool
     */
    public function delete($ChatVideo_id): Response
    {
        $ResponseType = new ResponseType();

        $ChatVideoController = new ChatVideoController();
        $ChatVideoController->delete($ChatVideo_id);

        return $this->respond($ResponseType);
    }

    /**
     * @param $ChatVideo_id
     * @return bool
     *
     * codes:
     * (200) 1 - Success
     * (200) 2 - profile_id must be not 0
     * (200) 3 - empty fields
     * (500) 1 - Server error on save
     */
    public function create(): Response
    {
        $ResponseType = new ResponseType();
        $ChatVideoController = new ChatVideoController();

        $code = $ChatVideoController->create($this->input['chat_video']);

        switch ($code) {
            case 1;
                $ResponseType->setHeaders(true, 200, 1);
                break;
            case 2;
                $ResponseType->setHeaders(true, 200, 2);
                break;
            case 3;
                $ResponseType->setHeaders(true, 200, 3);
                break;
            case 4;
                $ResponseType->setHeaders(true, 500, 1);
                break;
        }

        return $this->respond($ResponseType);
    }
}