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

$router->group(['prefix' => 'auth'], function () use ($router) {

    $router->post('/register', function () {
        // TODO: Routes this to the right controller
    });
    $router->post('/register', ['uses' => 'AuthController@register']);

    $router->post('/login', ['uses' => 'AuthController@login'], function () {
        // TODO: Routes this to the right controller
    });
});

$router->group(['prefix' => 'books'], function () use ($router) {
    $router->get('/', ['middleware' => 'jwt','uses' => 'BookController@index']);
    // $router->post('/', ['uses' => 'BookController@postBook']);
    
    $router->get('/{bookId}', ['uses' => 'BookController@getBookById']);
    // $router->put('/{bookId}', ['uses' => 'BookController@updateBook']);
    // $router->delete('/{bookId}', ['uses' => 'BookController@deleteBook']);
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        //TODO: Belum menggunakan middleware authorization dan beda user dengan admin
        $router->get('/{userId}', ['uses' => 'UserController@show']);

        $router->put('/{userId}', ['uses' => 'UserController@update']);

        $router->delete('/{userId}', ['uses' => 'UserController@destroy']);
    });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->get('/', function () {
            // TODO: Routes this to the right controller
        });

        $router->get('/{transactionId}', function () {
            // TODO: Routes this to the right controller
        });
    });
});

$router->group(['middleware' => 'auth:admin'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        //TODO: auth middleware belum
        $router->get('/', ['uses' => 'UserController@index']);
    });

    $router->group(['prefix' => 'books'], function () use ($router) {
        $router->post('/', ['uses' => 'BookController@postBook']);
        
        $router->get('/', ['middleware' => 'jwt', 'uses' => 'BookController@index']);

        $router->put('/{bookId}', function () {
            // TODO: Routes this to the right controller
        });

        $router->delete('/{bookId}', function () {
            // TODO: Routes this to the right controller
        });
    });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->put('/{transactionId}', function () {
            // TODO: Routes this to the right controller
        });
    });
});

$router->group(['middleware' => 'auth:user'], function () use ($router) {
    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->post('/', function () {
            // TODO: Routes this to the right controller
            return 'transaction';
        });
    });
});
