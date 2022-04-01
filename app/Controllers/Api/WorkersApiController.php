<?php

namespace App\Controllers\Api;

use App\Controllers\WorkersController;
use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use CodeIgniter\HTTP\Response;

class WorkersApiController extends BaseApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = ['getMyData'];

    /**
     * Get user data from authorisation. Returns WorkerEntity if
     * user exists, or ResponseType if not
     *
     * Return ResponseType | WorkerEntity
     *
     * Response codes:
     * (200) 1
     * (200) 2 - User not exists
     *
     * (401) 1 - Unauthorized
     */
    public function getMyData(): Response
    {
        $ResponseType = new ResponseType();

        $WorkersController = new WorkersController();
        $data = $WorkersController->getDataByUserID($this->user->id);

        if ($data === null) {
            $ResponseType->setHeaders(false, 200, 2);
        } else {
            $ResponseType->setHeaders(true, 200, 1);
            $ResponseType->setData($data);
        }

        return $this->respond($ResponseType);
    }


    /**
     * @return Response
     *
     *
     */
    public function registration(): Response
    {
        $ResponseType = new ResponseType();

        $check = check_required_fields([
            'bank_card', 'cardholder', 'city',
            'email', 'name', 'password',
            'phone', 'role'
        ], $this->input);

        if (!$check['status']) {
            $ResponseType->setHeaders(false, 200, 3);
            $ResponseType->setData($check['fields']);
            $this->respond($ResponseType);
        }

        $WorkersController = new WorkersController();
        $respond_code = $WorkersController->registration($this->input);


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
            default:
                $ResponseType->setHeaders(false, 500, 2);
                break;
        }

        return $this->respond($ResponseType);
    }
}