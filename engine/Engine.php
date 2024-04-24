<?php
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;

// load composer dependencies
require 'engine/vendor/autoload.php';

require 'Error.php';

// Set error handler function
function errorHandler($errno, $errstr, $errfile, $errline)
{
    throw new CustomError($errstr, 0, $errno, $errfile, $errline);
}


// Set error and exception handlers
set_error_handler("errorHandler");


/**
 * PHP Engine
 *
 * This PHP library provides a simple, flexible, and powerful PHP framework for building web applications.
 *
 * @version 1.1
 * @license GNU
 * @author Claude Amadu
 * @link https://github.com/claudeamadu/phpengine
 */
class Engine
{
    /**
     * Start
     *
     * @param string $directory
     * @return void
     */
    public static function Start(string $directory)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        $config = parse_ini_file('.env');

        if ($config === false) {
            die('Error: Unable to parse the INI file.');
        }
        foreach ($config as $key => $value) {
            define(strtoupper($key), $value);
        }
        require 'engine/extra/functions.php';
        require 'engine/extra/urls.php';

        spl_autoload_register(function ($className) use ($directory) {
            $baseDirs = [
                $directory . '/controllers/',
                $directory . '/middlewares/',
                $directory . '/models/',
                $directory . '/handlers/',
                $directory . '/helpers/',

                $directory . '/engine/',
                $directory . '/engine/controllers/',
                $directory . '/engine/handlers/',
                $directory . '/engine/helpers/',
                $directory . '/engine/middlewares/',
                $directory . '/engine/models/',
                $directory . '/engine/firebase/',
            ];

            foreach ($baseDirs as $baseDir) {
                $prefix = '';

                // Check if the class uses a namespace
                $len = strlen($prefix);
                if (strncmp($prefix, $className, $len) !== 0) {
                    continue;
                }

                // Trim the prefix and namespace separator
                $relativeClass = substr($className, $len);
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                if (file_exists($file)) {
                    require $file;

                    return;
                }
            }
        });


        /* Load external routes file */
        require_once 'routes/api/v' . API_VERSION . '.php';
        require_once 'routes/web.php';
        require_once 'routes/auth.php';


        // Start the routing
        Router::start();
    }
}