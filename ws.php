<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/app/Classes/Log.php';

use \App\Classes\Log;

use Workerman\Worker;

define('TCP_PORT', 'tcp://127.0.0.1:1234');
define('WS_PORT', 'websocket://api.444wt.net:2346');

Log::websocket([
    'message' => 'WS started by cli on ' . WS_PORT,
    'color' => 'success',
]);

$context = array(
    'ssl' => array(
        'local_cert'  => '/etc/letsencrypt/live/api.444wt.net/fullchain.pem',
        'local_pk'    => '/etc/letsencrypt/live/api.444wt.net/privkey.pem',
        'verify_peer' => false,
    )
);

// create a ws-server. all your users will connect to it
$ws_worker = new Worker(WS_PORT, $context);

$ws_worker->transport = 'ssl';

// storage of user-connection link
$users = [];

$ws_worker->onConnect = function ($connection) use (&$users) {
    $connection->onWebSocketConnect = function ($connection) use (&$users) {
        // put get-parameter into $users collection when a new user is connected
        // you can set any parameter on site page. for example client.html: ws = new WebSocket("ws://127.0.0.1:8000/?user=tester01");
        $users[$_GET['token']] = $connection;
        // or you can use another parameter for user identification, for example $_COOKIE['PHPSESSID']

        Log::websocket([
            'message' => 'WS new connection <b>' . $_GET['token'] . '</b>',
            'color' => 'default',
        ]);
    };
};

$ws_worker->onClose = function ($connection) use (&$users) {
    // unset parameter when user is disconnected
    $user = array_search($connection, $users);
    unset($users[$user]);

    Log::websocket([
        'message' => 'WS connection of <b>' . $user . '</b> closed',
        'color' => 'default',
    ]);
};

// it starts once when you start server.php:
$ws_worker->onWorkerStart = function () use (&$users) {
    // create a local tcp-server. it will receive messages from your site code (for example from send.php)
    $inner_tcp_worker = new Worker(TCP_PORT);

    Log::websocket([
        'message' => 'TCP Worker started on ' . TCP_PORT,
        'color' => 'success',
    ]);

    // create a handler that will be called when a local tcp-socket receives a message (for example from send.php)
    $inner_tcp_worker->onMessage = function ($connection, $data) use (&$users) {

        Log::websocket([
            'message' => 'TCP Worker get message',
            'color' => 'default',
            'data' => $data,
        ]);

        // you have to use for $data json_decode because send.php uses json_encode
        $data = json_decode($data); // but you can use another protocol for send data send.php to local tcp-server
        // send a message to the user by userId

        foreach ($data->tokens as $token) {
            if (isset($users[$token])) {
                $webconnection = $users[$token];
                $webconnection->send($data->message);
            }
        }

    };
    $inner_tcp_worker->listen();
};

// Run worker
Worker::runAll();