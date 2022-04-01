<?php

namespace App\Controllers\Api;

use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use App\Controllers\AuthController as AuthController;
use CodeIgniter\HTTP\Response;

class AuthApiController extends BaseApiController
{
    use ExtendResponseTrait;

    /**
     * @param string "client_id"
     * @param string "client_secret"
     * @param string "password"
     * @param string "username"
     *
     * @return array tokens
     *
     * Codes:
     * (200) 1 - success
     *
     * (401) 1 - Incorrect credentials
     * (401) 2 - Not active
     */
    public function login(): Response
    {
        $ResponseType = new ResponseType();
        $AuthController = new AuthController();

        $data = $AuthController->login();

        if ($data === 1) {
            $ResponseType->setHeaders(false, 401, 1);
            return $this->respond($ResponseType);
        } elseif ($data === 2) {
            $ResponseType->setHeaders(false, 401, 2);
            return $this->respond($ResponseType);
        } else {
            $ResponseType->setHeaders(true, 200, 1);
            $ResponseType->setData($data);
            return $this->respond($ResponseType, true);
        }
    }

    public function refresh(): Response
    {
        $AuthController = new AuthController();

        $ResponseType = new ResponseType();

        $data = $AuthController->refresh();

        if ($data === false) {
            $ResponseType->setHeaders(false, 401, 1);
        } else {
            $ResponseType->setHeaders(true, 200, 1);
            $ResponseType->setData($data);
        }

        return $this->respond($ResponseType, true);
    }


    /**
     * Just logout
     *
     * @return void
     */
    public function logout(): Response
    {
        $AuthController = new AuthController();

        $AuthController->logout();

        $ResponseType = new ResponseType();
        $ResponseType->setHeaders(true, 200, 1);

        return $this->respond($ResponseType);
    }
}