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
    $router->post('users/info', 'AuthController@info');
    $router->post('users/logout', 'AuthController@logout');
    $router->get('users', 'UserController@getData');

    $router->get('transactions', 'TransController@getData');

    $router->get('roles', 'RolesController@getRoles');
    $router->get('routes', 'RolesController@getRoutes');
    $router->post('roles', 'RolesController@createRoles');
    $router->put('roles/{id}', 'RolesController@updateRoles');
    $router->delete('roles/{id}', 'RolesController@deleteRoles');
    $router->get('permission/{id}', 'RolesController@getPermission');
});