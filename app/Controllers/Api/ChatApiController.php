<?php

namespace App\Controllers\Api;

use App\Controllers\ChatController;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use CodeIgniter\HTTP\Response;

class ChatApiController extends BaseApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'getChatHistory' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
            WorkerEntity::ROLE_CHAT_OPERATOR,
        ],
        'sendMessage' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
    ];

    public function getChatHistory()
    {
        $ChatController = new ChatController();

        $data = $ChatController->getChatHistory($this->input['man_id'], $this->input['profile_id']);

        $ResponseType = new ResponseType();
        $ResponseType->setHeaders(true, 200, 1);
        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    /**
     * @return Response
     *
     * Codes:
     * 1 - success
     * 2 - error
     */
    public function sendMessage()
    {
        $ChatController = new ChatController();

        $response_code = $ChatController->sendMessage($this->input['man_id'], $this->input['profile_id'], $this->input['message']);

        $ResponseType = new ResponseType();

        switch ($response_code) {
            case 1:
                $ResponseType->setHeaders(true, 200, 1);
                break;
            default:
                $ResponseType->setHeaders(false, 200, 2);
                break;
        }

        return $this->respond($ResponseType);
    }
}