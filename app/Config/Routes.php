<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->options('api/(:any)', 'Api\BaseApiController::options');

$routes->group('', ['namespace' => 'App\Controllers\View'], function ($routes) {
    $routes->add('start', 'ChatConsoleController::updateMessages');
});

$routes->group('cron', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->cli('every5seconds', 'CronController::every5seconds');
    $routes->cli('everyMinute', 'CronController::everyMinute');
    $routes->cli('everyHour', 'CronController::everyHour');
    $routes->cli('everyDay', 'CronController::everyDay');
    $routes->cli('everyMonday12am', 'CronController::everyMonday12am');
});

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
});

$routes->group('console', ['namespace' => 'App\Controllers\Console'], function ($routes) {
});

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
