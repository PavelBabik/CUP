<?php

namespace App\Controllers;

use App\Libraries\Files as FilesLibrary;
use App\Models\FilesModel;

class FilesController extends BaseController
{
    private $Files;
    public function __construct()
    {
        $this->Files = new FilesLibrary();
    }

    public function uploadFile(int $worker_id)
    {
        return $this->Files->uploadFile($worker_id);
    }

    public function getFile(string $file_name)
    {
        if (strlen($file_name) != 32) {
            return false;
        }

        return $this->Files->renderFile($file_name);
    }

    public function getFileThumbnail(string $file_name)
    {
        if (strlen($file_name) != 32) {
            return false;
        }

        return $this->Files->renderFile($file_name . '_thumb');
    }

    public function getFileThumbnailByID(int $file_id)
    {
        $FilesModel = new FilesModel();
        $fileData = $FilesModel->find($file_id);

        $file = $this->getFileThumbnail($fileData->name);

        return $file;
    }

    public function getFileByID(int $file_id)
    {
        $FilesModel = new FilesModel();
        $fileData = $FilesModel->find($file_id);

        $file = $this->getFile($fileData->name);

        return $file;
    }
}