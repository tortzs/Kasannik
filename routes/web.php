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
 * Instructor
 */
// get
$router->get('/instructor', 'InstructorController@instructor');
$router->get('/instructor/edit/{instructor}', 'InstructorController@instructorEdit');
$router->get('/instructor/add', 'InstructorController@instructorAdd');
// post
$router->post('/instructor/delete', 'InstructorController@instructorDelete');
$router->post('/instructor/insert', 'InstructorController@instructorInsert');
$router->post('/instructor/update', 'InstructorController@instructorUpdate');
/*
 * Semester
 */
// get
$router->get('/semester', 'SemesterController@semester');
$router->get('/semester/edit/{semester}', 'SemesterController@semesterEdit');
$router->get('/semester/add', 'SemesterController@semesterAdd');
$router->get('/semester/view/{semester}', 'SemesterController@semesterView');;
// post
$router->post('/semester/delete', 'SemesterController@semesterDelete');
$router->post('/semester/insert', 'SemesterController@semesterInsert');
$router->post('/semester/update', 'SemesterController@semesterUpdate');

/*
 * User
 */
// get
$router->get('/register', 'AuthController@userRegister');
$router->get('/login', 'AuthController@userLogin');
$router->get('/user', 'AuthController@user');
$router->get('/user/logout', 'AuthController@logout');
// post
$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');

/*
 * Subject
 */
// get
$router->get('/subject/view/{subject}', 'SubjectController@subjectView');
// post
$router->post('/subject/insert', 'SubjectController@subjectInsert');
$router->post('/subject/delete', 'SubjectController@subjectDelete');

/*
 * Assignment
 */

//post
$router->post('/assignment/insert', 'AssignmentController@assignmentInsert');
$router->post('/assignment/delete', 'AssignmentController@assignmentDelete');
$router->post('/assignment/update', 'AssignmentController@assignmentUpdate');