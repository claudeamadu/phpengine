<?php
$host = $_SERVER['HTTP_HOST'];
if ($host == 'localhost') {
    define('ROOT_URL',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".ROUTE_URL);
    define('CURRENT_URL',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    define('CURRENT_ROUT_URL',$_SERVER['REQUEST_URI']);
} else {
    define('ROOT_URL',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".ROUTE_URL);
    define('CURRENT_URL',(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    define('CURRENT_ROUT_URL',$_SERVER['REQUEST_URI']);
}
?>