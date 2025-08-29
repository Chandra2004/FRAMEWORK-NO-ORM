<?php
// ROUTER
use TheFramework\App\Router;

// MIDDLEWARE
use TheFramework\Middleware\WAFMiddleware;

// CONTROLLER
use TheFramework\Http\Controllers\HomeController;
use TheFramework\Middleware\CsrfMiddleware;

Router::add('GET', '/', HomeController::class, 'Welcome', [WAFMiddleware::class]);
Router::add('GET', '/users', HomeController::class, 'Users', [WAFMiddleware::class]);
Router::group(
    [
        'prefix' => '/users',
        'middleware' => [
            CsrfMiddleware::class,
            WAFMiddleware::class
        ]
    ],
    function () {
        Router::add('POST', '/create', HomeController::class, 'CreateUser');
        Router::add('POST', '/update/{uid}', HomeController::class, 'UpdateUser');
        Router::add('POST', '/delete/{uid}', HomeController::class, 'DeleteUser');

        Router::add('GET', '/information/{uid}', HomeController::class, 'InformationUser');
    }
);
