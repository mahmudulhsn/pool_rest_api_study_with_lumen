<?php

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

$router->group([
    'prefix' => 'api/v1'
], function() use($router){
    $router->get('/', 'ExampleController@index');

    // users authenticate
    $router->post('/login', 'UserController@authenticate');

    // users resource
    $router->post('/users', 'UserController@create');

    // restricted routes
    $router->group(['middleware' => 'auth:api'], function() use($router) {
        $router->get('/users', 'UserController@index');
        $router->get('/me', 'UserController@me');
    });
});
