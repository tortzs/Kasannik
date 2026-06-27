<?php
/** @var Router $router */


/*
 * Home
 */
$router->get('/', 'HomeController@index');
/*
 * Schedule
 */
$router->get('/schedule', 'ScheduleController@schedule');
$router->get('/schedule/edit', 'ScheduleController@scheduleEdit');

/*
 * User
 */
$router->get('/register', 'AuthController@userRegister');
$router->get('/login', 'AuthController@userLogin');
$router->get('/user', 'AuthController@user');

/*
 * Instructor
 */
// get
$router->get('/instructor', 'InstructorController@instructor');
$router->get('/instructor/edit', 'InstructorController@instructorEdit');
$router->get('/instructor/add', 'InstructorController@instructorAdd');
// post
$router->post('/instructor/delete', 'InstructorController@instructorDelete');
$router->post('/instructor/insert', 'InstructorController@instructorInsert');
$router->post('/instructor/update', 'InstructorController@instructorUpdate');

/*
 * User
 */
// get
$router->get('/user/logout', 'AuthController@logout');
// post
$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');
