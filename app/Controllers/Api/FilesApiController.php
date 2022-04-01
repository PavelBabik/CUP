<?php

namespace App\Controllers\Api;

use App\Controllers\FilesController;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use CodeIgniter\HTTP\Response;

class FilesApiController extends BaseApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'uploadFile' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getFile' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getFileByID' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getFileThumbnail' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getFileThumbnailByID' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
    ];

    public function uploadFile(): Response //TODO: Проверять размер картинки
    {
        $ResponseType = new ResponseType();

        $FilesController = new FilesController();

        $data = $FilesController->uploadFile($this->user->id);

        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    public function getFile($file_name)
    {
        $FilesController = new FilesController();

        $data = $FilesController->getFile($file_name);

        if ($data === false) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/invalid_file_name.jpg');
        } elseif ($data === null) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/file_not_found.jpg');
        }

        $this->response->setContentType($data['mime']);
        return $data['file'];
    }

    public function getFileThumbnail($file_name)
    {
        $FilesController = new FilesController();

        $data = $FilesController->getFileThumbnail($file_name);

        if ($data === false) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/invalid_file_name.jpg');
        } elseif ($data === null) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/thumbnail_not_found.jpg');
        }

        $this->response->setContentType($data['mime']);
        return $data['file'];
    }
    
    public function getFileThumbnailByID($file_id)
    {
        $FilesController = new FilesController();

        $data = $FilesController->getFileThumbnailByID($file_id);

        if ($data === false) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/invalid_file_ID.jpg');
        } elseif ($data === null) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/thumbnail_not_found.jpg');
        }

        $this->response->setContentType($data['mime']);
        return $data['file'];
    }

    public function getFileByID($file_id)
    {
        $FilesController = new FilesController();

        $data = $FilesController->getFileByID($file_id);

        if ($data === false) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/invalid_file_ID.jpg');
        } elseif ($data === null) {
            $this->response->setContentType('image/jpeg');
            return file_get_contents(ROOTPATH . 'public/media_errors/file_not_found.jpg');
        }

        $this->response->setContentType($data['mime']);
        return $data['file'];
    }
}