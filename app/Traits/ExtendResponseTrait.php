<?php

namespace App\Traits;

use App\Types\ResponseType;
use CodeIgniter\API\ResponseTrait;

trait ExtendResponseTrait
{
    use ResponseTrait {
        ResponseTrait::respond as parentRespond;
    }

    public function respond(ResponseType $ResponseType, bool $only_data = false)
    {
        if ($only_data) {
            return $this->parentRespond($ResponseType->data, $ResponseType->http_code);
        } else {
            return $this->parentRespond($ResponseType, $ResponseType->http_code);
        }
    }
}