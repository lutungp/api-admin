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

    $router->get('transactions', 'TransController@getData');

    $router->get('roles', 'RolesController@getRoles');
    $router->get('routes', 'RolesController@getRoutes');
    $router->post('roles', 'RolesController@createRoles');
    $router->put('roles/{id}', 'RolesController@updateRoles');
    $router->delete('roles/{id}', 'RolesController@deleteRoles');
    $router->get('permission/{id}', 'RolesController@getPermission');

    $router->get('satuan', 'SatuanController@getSatuan');
    $router->get('satuan_list', 'SatuanController@getSatuanlist');
    $router->post('satuan', 'SatuanController@createSatuan');
    $router->put('satuan/{id}', 'SatuanController@updateSatuan');
    $router->delete('satuan/{id}', 'SatuanController@deleteSatuan');

    $router->get('bahan', 'BahanController@getBahan');
    $router->post('bahan', 'BahanController@createBahan');
    $router->put('bahan/{id}', 'BahanController@updateBahan');
    $router->delete('bahan/{id}', 'BahanController@deleteBahan');

    $router->get('produk', 'ProdukController@getProduk');
    $router->get('produklist', 'ProdukController@getProdukList');
    $router->post('produk', 'ProdukController@createProduk');
    $router->put('produk/{id}', 'ProdukController@updateProduk');
    $router->delete('produk/{id}', 'ProdukController@deleteProduk');

    $router->get('prodkategori', 'ProdKategoriController@getProdKategori');
    $router->get('prodkategorilist', 'ProdKategoriController@getProdKategoriList');
    $router->post('prodkategori', 'ProdKategoriController@createProdKategori');
    $router->put('prodkategori/{id}', 'ProdKategoriController@updateProdKategori');
    $router->delete('prodkategori/{id}', 'ProdKategoriController@deleteProdKategori');
});
