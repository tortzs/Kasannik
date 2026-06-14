<?php

$router->get('/', 'HomeController@index');
$router->get('/schedule', 'HomeController@schedule');
$router->get('/schedule/edit', 'HomeController@scheduleEdit');
