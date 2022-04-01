<?php

namespace App\Controllers\Api\Admin;

use App\Classes\Profile;
use App\Controllers\ProfilesController;
use App\Entities\WorkerEntity;
use App\Traits\ExtendResponseTrait;
use App\Types\ResponseType;
use Codeigniter\HTTP\Response;

class AdminProfilesApiController extends BaseAdminApiController
{
    use ExtendResponseTrait;

    public $protectedPaths = [
        'getProfilesList' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'getOneProfile' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'deleteProfile' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'addProfile' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'editProfile' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
        'setAccessID' => [
            WorkerEntity::ROLE_SUPER_ADMIN,
        ],
    ];

    /**
     * @return Response
     */
    public function getProfilesList(): Response
    {
        $ResponseType = new ResponseType();

        $ProfilesController = new ProfilesController();
        $data = $ProfilesController->getProfilesList();
        $ResponseType->setData($data);

        return $this->respond($ResponseType);
    }

    /**
     * @param $profile_id
     * @return Response
     *
     * Codes:
     * (200) 1 - success
     * (404) 1 - user not found
     */
    public function getOneProfile($profile_id): Response
    {
        $ResponseType = new ResponseType();
        $ProfilesController = new ProfilesController();

        $data = $ProfilesController->getDataByProfileID($profile_id);

        if ($data === null) {
            $ResponseType->setHeaders(false, 404);
        } else {
            $ResponseType->setData($data);

            return $this->respond($ResponseType);
        }
        return $this->respond($ResponseType);
    }

    /**
     * @param $profile_id
     * @return Response
     */
    public function deleteProfile($profile_id): Response
    {
        $ResponseType = new ResponseType();

        $Profiles = new Profile();
        $Profiles->delete($profile_id);

        return $this->respond($ResponseType);
    }

    /**
     * @return Response
     *
     * codes:
     * (200) 1 - success
     * (200) 2 - busy site_id
     * (200) 3 - id must not exist
     * (500) 1 - error on save to DataBase
     */
    public function addProfile(): Response
    {
        $ResponseType = new ResponseType();
        $ProfilesController = new ProfilesController();

        $code = $ProfilesController->addProfile($this->input['profile']);

        switch ($code) {
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
        }

        return $this->respond($ResponseType);
    }

    /**
     * @return Response
     *
     * Codes:
     * (200) 1 - success
     * (500) 2 - not success
     * (200) 3 - empty fields
     * (404) 4 - user not found
     */
    public function editProfile(): Response
    {
        $ResponseType = new ResponseType();
        $ProfilesController = new ProfilesController();

        $code = $ProfilesController->editProfile($this->input['profile']);

        switch ($code) {
            case 1:
                $ResponseType->setHeaders(true, 200, 1);
                break;
            case 2:
                $ResponseType->setHeaders(false, 500, 1);
                break;
            case 3:
                $ResponseType->setHeaders(false, 200, 3);
                break;
            case 4:
                $ResponseType->setHeaders(false, 404, 1);
                break;

        }
        return $this->respond($ResponseType);
    }

    /*
     * Set access ID for profile
     */
    public function setAccessID(): Response
    {
        $ResponseType = new ResponseType();
        $ProfilesController = new ProfilesController();

        $ProfilesController->setAccessID($this->input['profile_id'], $this->input['access_id']);

        return $this->respond($ResponseType);
    }
}