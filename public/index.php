<?php

session_start();


define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');


spl_autoload_register(function ($className) {
    $paths = [
            APP_PATH . '/Core/' . $className . '.php',
            APP_PATH . '/Controllers/' . $className . '.php',
            APP_PATH . '/Models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});


require_once APP_PATH . '/config/config.php';


$router = new Router();


require_once ROOT_PATH . '/routes/web.php';


$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);