<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;



Router::get(ROUTE_URL, function () {
    return View::make('login');
});
Router::get(ROUTE_URL . 'login', function () {
    return View::make('login');
});
Router::get(ROUTE_URL . 'logout', function () {
    Session::unset('user_id');
    SessionMessage::setMessage("Hope to see you again", "", "success");
    return View::make('login');
});
Router::get(ROUTE_URL . 'register', function () {
    return View::make('register');
});
Router::get(ROUTE_URL . 'recover-password', function () {
    return View::make('recover-password');
});

Router::get(ROUTE_URL . 'engine/methods', function () {
    displayClassMethods('Database');
    displayClassMethodsFromFile('engine/Firebase.php');
    displayClassMethods('Http');
    displayClassMethods('Route');
    displayClassMethods('View');
    displayClassMethods('ResponseHandler');
    displayClassMethods('Session');
    displayClassMethods('SessionMessage');
    displayClassMethods('ContactImporter');
    displayClassMethods('CryptoHelper');
    displayClassMethods('DirectoryManager');
    displayClassMethods('FileManager');
    displayClassMethods('FileUploader');
    displayClassMethods('UserInfo');
    displayClassMethods('Auth');
    displayClassMethods('Mailer');
    displayClassMethods('Paystack');
    displayClassMethods('User');
    displayFunctionsFromFile('engine/extra/functions.php');
});

Router::get(ROUTE_URL . 'not-found', 'ErrorPage@notFound');
Router::get(ROUTE_URL . 'forbidden', 'ErrorPage@forbidden');
Router::error(function (Request $request, \Exception $exception) {
    switch ($exception->getCode()) {
        // Page not found
        case 404:
            echo view::error(404);
            break;
        // Forbidden
        case 403:
            echo view::error(404);
            break;
    }

});