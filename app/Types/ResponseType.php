<?php

namespace App\Types;

use App\Interfaces\TypeInterface;

class ResponseType extends BaseType implements TypeInterface
{
    public bool $success = true;
    public int $http_code = 200;
    public int $response_code = 1;
    public $data = null;

    public function setHeaders(bool $success = true, int $http_code = 200, int $response_code = 1)
    {
        $this->success = $success;
        $this->http_code = $http_code;
        $this->response_code = $response_code;
    }

    public function setData($payload)
    {
        $this->data = $payload;
    }
}