<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Controllers\WorkersController;
use App\Libraries\Oauth;

class BaseApiController extends BaseController
{

    protected $input;
    protected $oauth;
    protected $user;

    public $protectedPaths = [];

    public function __construct()
    {
        $rawInput = file_get_contents('php://input');
        $this->input = json_decode($rawInput, true);

        $this->oauth = new Oauth();

        $login = $this->oauth->getUserLogin();

        if ($login != NULL) {
            $WorkersController = new WorkersController();
            $this->user = $WorkersController->getUserDataByUsernameOrEmail($login);
        }
        
        $router = \CodeIgniter\Config\Services::router();

        if (isset($this->protectedPaths[$router->methodName()])) {
            if (!$this->isLoggedIn()) {
                http_response_code(401);
                die;
            } else {
                if (is_array($this->protectedPaths[$router->methodName()])) {
                    if (!in_array($this->user->role, $this->protectedPaths[$router->methodName()])) {
                        http_response_code(403);
                        die;
                    }
                }
            }
        } elseif (in_array($router->methodName(), $this->protectedPaths)) {
            if (!$this->isLoggedIn()) {
                http_response_code(401);
                die;
            }
        }
    }

    public function options()
    {
        return true;
    }

    protected function isLoggedIn()
    {
        if (isset($this->user->id)) {
            return true;
        } else {
            return false;
        }
    }
}