<?php namespace App\Libraries;

use \OAuth2\Storage\Pdo;

class Oauth
{
    var $server;

    function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $dsn = getenv('database.default.DSN');
        $username = getenv('database.default.username');
        $password = getenv('database.default.password');

        $storageConfig = [
            'refresh_token_table' => 'oauth_refresh_tokens',
            'access_token_table'  => 'oauth_access_tokens',
            'public_key_table'    => 'oauth_public_keys',
            'client_table'        => 'oauth_clients',
            'scope_table'         => 'oauth_scopes',
            'code_table'          => 'oauth_authorization_codes',
            'user_table'          => 'workers',
            'jwt_table'           => 'oauth_jwt',
            'jti_table'           => 'oauth_jti',
        ];

        $config = [
            'access_lifetime' => 86400, // 1 day
            'refresh_token_lifetime' => 2419200, // 28 days
            'always_issue_new_refresh_token' => true,
            'unset_refresh_token_after_use' => true,
        ];

        $storage = new Pdo(
            [
                'dsn'      => $dsn,
                'username' => $username,
                'password' => $password
            ],
            $storageConfig
        );

        $this->server = new \OAuth2\Server($storage, $config);

        $grantTypes = [
            'user_credentials' => 'UserCredentials',
            'client_credentials' => 'ClientCredentials',
            'authorization_code' => 'AuthorizationCode',
            'refresh_token' => 'RefreshToken'
        ];

        $serverGrants = [
            'user_credentials',
            'refresh_token',
        ];

        foreach ($serverGrants as $grant) {
            if (isset($grantTypes[$grant])) {
                $class = 'OAuth2\\GrantType\\'. $grantTypes[$grant];
                $grantClass = new $class($storage, $config);

                $this->server->addGrantType($grantClass);
            } else {
                throw new \Exception("Grant type ". $grant ." could not be added to server. It does not exist. Please use on of these". print_r($grantTypes));
            }
        }

//        $this->server->addGrantType(new \OAuth2\GrantType\UserCredentials($storage));
//        $this->server->addGrantType(new \OAuth2\GrantType\RefreshToken($storage));
    }

    public function getUserLogin()
    {
        $data = $this->server->getAccessTokenData(\OAuth2\Request::createFromGlobals());

        if ($data != NULL) {
            return $data['user_id'];
        } else {
            return NULL;
        }
    }

    public function getUserToken()
    {
        $data = $this->server->getAccessTokenData(\OAuth2\Request::createFromGlobals());

        if ($data != NULL) {
            return $data['access_token'];
        } else {
            return NULL;
        }
    }
}