<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/password', 'Api\UserController@changePassword');
Route::resource('users', 'Api\UserController');
Route::resource('encargados', 'Api\EncargadoController');
Route::resource('categorias', 'Api\CategoriaController');
Route::resource('premios', 'Api\PremioController');
Route::resource('disciplinas', 'Api\DisciplinaController');
Route::resource('meritos', 'Api\MeritoController');
Route::get('cadetes/search', 'Api\CadeteController@getAllByFilter');
Route::resource('demeritos', 'Api\DemeritoController');
