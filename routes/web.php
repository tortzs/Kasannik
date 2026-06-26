<?php
/** @var Router $router */
/*
 * HOME CONTROLLER
 */
$router->get('/', 'HomeController@index');
$router->get('/schedule', 'HomeController@schedule');
$router->get('/schedule/edit', 'HomeController@scheduleEdit');
$router->get('/register', 'HomeController@userRegister');
$router->get('/login', 'HomeController@userLogin');
/*
 * AUTH CONTROLLER
 */
$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');
$router->get('/user/logout', 'AuthController@logout');
