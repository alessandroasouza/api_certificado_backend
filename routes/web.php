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
/*
$router->get('/', function () use ($router) {
    return $router->app->version();
});
*/

// API route group

$router->group(['prefix' => 'api/user'], function () use ($router) {
    $router->post('/store', 'AuthController@store');
    $router->post('/login', 'AuthController@login');
    
    $router->get('/perfil', 'UserController@perfil');
    $router->get('/{id}', 'UserController@show');
    $router->get('/', 'UserController@index');
    $router->post('/update', 'UserController@update');
    $router->post('/delete', 'UserController@destroy');
 });



Route::group(['middleware' => 'auth'], function ($router) {
//Rotas com autenticação ou definir na instancia da classe

});