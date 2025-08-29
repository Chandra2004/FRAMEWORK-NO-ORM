<?php

namespace TheFramework\Http\Controllers\Services;

use TheFramework\App\Config;
use TheFramework\App\View;

class ErrorController
{
    public static function error403()
    {
        http_response_code(403);
        $model = [];

        View::render('errors.403', $model);
    }

    public static function error404()
    {
        http_response_code(404);
        $model = [];

        View::render('errors.404', $model);
    }

    public static function error500()
    {
        http_response_code(500);
        $model = [];

        View::render('errors.500', $model);
    }

    public static function payment()
    {
        $model = [];

        View::render('errors.payment', $model);
    }

    public static function maintenance()
    {
        $model = [];

        View::render('errors.maintenance', $model);
    }
}
