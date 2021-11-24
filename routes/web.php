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
    $router->post('/register', ['uses' => 'AuthController@register']);

    $router->post('/login', ['uses' => 'AuthController@login']);
});

$router->group(['prefix' => 'books'], function () use ($router) {
    $router->get('/', ['uses' => 'BookController@index']);
    
    
    $router->get('/{bookId}', ['uses' => 'BookController@getBookById']);
    
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/{userId}', ['uses' => 'UserController@show']);//diarahan ke getUseerByIdUserSelf (cek nk user idne podo karo seng login baru tampil)

        $router->put('/{userId}', ['uses' => 'UserController@update']);

        $router->delete('/{userId}', ['uses' => 'UserController@destroy']);
    });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        
        $router->get('/', ['uses' => 'TransactionController@index']);//cek transaction that same user id who loged in
        $router->get('/{transactionId}', ['uses' => 'TransactionController@getTransactionById']);//admin only
        
    });
});

$router->group(['middleware' => 'auth:admin'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/', ['uses' => 'UserController@index']);
        //admin juga bisa lihat /user/{id} tp func getuserbyid 
    });

    $router->group(['prefix' => 'books'], function () use ($router) {
        $router->post('/', ['uses' => 'BookController@postBook']);
        $router->put('/{bookId}', ['uses' => 'BookController@updateBook']);
        $router->delete('/{bookId}', ['uses' => 'BookController@deleteBook']);
    });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->put('/{transactionId}', ['uses' => 'TransactionController@updateTransaction']);
    });
});

$router->group(['middleware' => 'auth:user'], function () use ($router) {
    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->post('/', ['uses' => 'TransactionController@postTransaction']);
    });
});
