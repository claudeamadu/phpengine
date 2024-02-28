<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;


Router::group(['middleware' => Auth::class], function () {
    Router::get(ROUTE_URL . 'app', function () {
        return View::make('app');
    });
    Router::get(ROUTE_URL . 'dashboard', function () {
        return View::make('dashboard');
    });
});