<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Models\Inscricao;

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
    
    $router->post('/reset', 'AuthController@reset');
    $router->post('/validatetoken', 'AuthController@validatetoken');

    
    $router->get('/perfil', 'UserController@perfil');
    $router->get('/{id}', 'UserController@show');
    $router->get('/', 'UserController@index');
    $router->post('/update', 'UserController@update');
    $router->post('/delete', 'UserController@destroy');
    $router->post('/logout', 'UserController@logout');
 });

 
$router->group(['prefix' => 'api/eventos'], function () use ($router) {
    $router->get('/', 'EventosController@index');
    $router->post('/store', 'EventosController@store');
    $router->get('/{id}', 'EventosController@show');
    $router->post('/delete', 'EventosController@destroy');
    $router->post('/update', 'EventosController@update');
});



$router->group(['prefix' => 'api/user'], function () use ($router) {
    $router->post('/resetPassword', 'AuthController@resetPassword'); 
});


 //Rotas com autenticação ou definir na instancia da classe
Route::group(['middleware' => 'auth'], function ($router) {
      
    $router->group(['prefix' => 'api/inscricao'], function () use ($router){
        $router->get('/index', 'InscricaoController@index');
        $router->get('/show/{id}', 'InscricaoController@index');
        $router->get('/details/{id}', 'InscricaoController@details');
        $router->get('/listfull/{id}', 'InscricaoController@listfulluser');
        $router->get('/listevent/{id}', 'InscricaoController@listevent');
        $router->post('/store', 'InscricaoController@store');
        $router->post('/update', 'InscricaoController@update');
        $router->post('/delete', 'InscricaoController@destroy');
        $router->post('/attendanceone', 'InscricaoController@attendanceone');
        $router->post('/attendancetwo', 'InscricaoController@attendancetwo');
        $router->post('/hascertificate', 'InscricaoController@hascertificate');
        $router->post('/certificateevent', 'InscricaoController@certificateevent');
        $router->post('/activeattendanceone', 'InscricaoController@activeattendanceone');
        $router->post('/activeattendancethow', 'InscricaoController@activeattendancethow');
        
    });
    
});