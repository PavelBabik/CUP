<?php

namespace App\Controllers\View;

use App\Classes\Log;

class AdminViewController extends BaseViewController
{
    public function __construct()
    {
        $this->setMiddleware();

        parent::__construct();
    }

    public function index()
    {
        return view('main', $this->data);
    }

    public function login()
    {
        $session = session();

        if (isset($_POST['login'])) {
            if ($_POST['login'] == getenv('ADMIN_LOGIN') && sha1($_POST['password']) == getenv('ADMIN_PASSWORD')) {
                $session->set('auth', $_POST['login'] . '_' . sha1($_POST['password']));
                return redirect()->to('/admin');
            } else {
                $this->data['error_msg'] = 'Incorrect credentials';
                return view('login', $this->data);
            }
        } else {
            return view('login', $this->data);
        }
    }

    public function logout()
    {
        $session = session();

        $session->destroy();

        return redirect()->to('/admin/login');
    }
}