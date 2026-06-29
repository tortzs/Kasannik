<?php

/*
 * Naprawa dla Matiego bo cos zepsul, potem mozna usunac
 */

if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
}

session_start();
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentPath = rtrim($currentPath, '/');

if ($currentPath === '') {
    $currentPath = '/';
}

$publicRoutes = [
    '/',
    '/login',
    '/register',
    '/auth/login',
    '/auth/register',
];

$isLoggedIn = isset($_SESSION['userID']);
$isPublicRoute = in_array($currentPath, $publicRoutes, true);

if (!$isLoggedIn && !$isPublicRoute) {
    header('Location: /login');
    exit;
}

if ($isLoggedIn && $currentPath === '/login') {
    header('Location: /');
    exit;
}

$userId = $isLoggedIn ? (int) $_SESSION['userID'] : null;


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
if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

}