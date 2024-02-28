<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;

Router::group(['prefix' => ROUTE_URL . 'v' . API_VERSION . '/'], function () {
    Router::post('login', function () {
        $auth = User::authenticate();
        if ($auth) {
            Route::head('app');
        } else {
            SessionMessage::setMessage("Invalid Credentials", "", "error");
            Route::head('login');
        }
    });

    Router::post('register', function () {
        $auth = User::create([
            'name' => requestData('name'),
            'email' => requestData('email'),
            'password' => password(requestData('password')),
            'contact' => requestData('contact'),
            'role' => 'user'
        ]);
        if ($auth) {
            SessionMessage::setMessage("Resgistration Successful", "", "success");
            Route::head('login');
        } else {
            SessionMessage::setMessage("Invalid Data", "", "error");
            Route::head('register');
        }
    });
});