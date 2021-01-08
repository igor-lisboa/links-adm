<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api/v1', 'middleware' => ['auth']], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->post('/login', 'UserController@login');
        $router->post('/register', 'UserController@register');
    });

    $router->group(['prefix' => 'links'], function () use ($router) {
        $router->post('/', 'LinkController@store');
        $router->delete('/{id}', 'LinkController@delete');
    });

    $router->group(['prefix' => 'categories'], function () use ($router) {
        $router->get('/', 'CategoryController@index');
        $router->post('/', 'CategoryController@store');
        $router->delete('/{id}', 'CategoryController@delete');
        $router->get('/{id}/links', 'CategoryController@links');
    });
});
