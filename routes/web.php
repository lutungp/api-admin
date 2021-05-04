<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use \Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('users/login', 'AuthController@login');
    $router->get('users/info', 'AuthController@info');
    $router->post('users/logout', 'AuthController@logout');

    $router->get('users', 'UserController@getUsers');
    $router->post('users', 'UserController@createUsers');
    $router->put('users/{id}', 'UserController@updateUsers');
    $router->delete('users/{id}', 'UserController@deleteUsers');

    $router->get('users/activity', 'UserController@getActivity');

    $router->get('roles', 'RolesController@getRoles');
    $router->get('routes', 'RolesController@getRoutes');
    $router->post('roles', 'RolesController@createRoles');
    $router->put('roles/{id}', 'RolesController@updateRoles');
    $router->delete('roles/{id}', 'RolesController@deleteRoles');
    $router->get('permission/{id}', 'RolesController@getPermission');

    $router->get('activity', 'ActivityController@getActivity');
    $router->post('activity', 'ActivityController@createActivity');
    $router->put('activity/{id}', 'ActivityController@updateActivity');
    $router->delete('activity/{id}', 'ActivityController@deleteActivity');
    $router->put('activity/confirm/{id}', 'ActivityController@confirmActivity');
});
