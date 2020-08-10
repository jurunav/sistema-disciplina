<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});


Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['ComandanteEscuadron']], function () {

        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/premio', 'PremioController@index');
        Route::post('/premio/registrar', 'PremioController@store');
        Route::put('/premio/actualizar', 'PremioController@update');
        Route::put('/premio/desactivar', 'PremioController@desactivar');
        Route::put('/premio/activar', 'PremioController@activar');

        Route::get('/sancion', 'SancionController@index');
        Route::post('/sancion/registrar', 'SancionController@store');
        Route::put('/sancion/actualizar', 'SancionController@update');
        Route::put('/sancion/desactivar', 'SancionController@desactivar');
        Route::put('/sancion/activar', 'SancionController@activar');

        Route::get('/oficial', 'OficialController@index');
        Route::post('/oficial/registrar', 'OficialController@store');
        Route::put('/oficial/actualizar', 'OficialController@update');
        Route::put('/oficial/desactivar', 'OficialController@desactivar');
        Route::put('/oficial/activar', 'OficialController@activar');
        Route::get('/oficial/selectOficial', 'OficialController@selectOficial');

        Route::get('/cadete', 'CadeteController@index');
        Route::post('/cadete/registrar', 'CadeteController@store');
        Route::put('/cadete/actualizar', 'CadeteController@update');
        Route::put('/cadete/desactivar', 'CadeteController@desactivar');
        Route::put('/cadete/activar', 'CadeteController@activar');

        //Route::get('/rol', 'RolController@index');
        //Route::post('/rol/registrar', 'RolController@store');
        //Route::put('/rol/actualizar', 'RolController@update');
        //Route::put('/rol/desactivar', 'RolController@desactivar');
        //Route::put('/rol/activar', 'RolController@activar');
        //Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::resource('/user', 'UserController');

        Route::resource('/inicio', 'InicioController');

        Route::resource('/rol', 'RolController');
        Route::get('/report/franco-de-honor', 'GenerateReportController@listarFrancoDeHonor');
        Route::get('/report/control-merito-demerito', 'GenerateReportController@controlMeritoDemerito');
    });

    Route::group(['middleware' => ['JefeSeccion']], function () {

        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/premio', 'PremioController@index');
        Route::post('/premio/registrar', 'PremioController@store');
        Route::put('/premio/actualizar', 'PremioController@update');
        Route::put('/premio/desactivar', 'PremioController@desactivar');
        Route::put('/premio/activar', 'PremioController@activar');

        Route::get('/sancion', 'SancionController@index');
        Route::post('/sancion/registrar', 'SancionController@store');
        Route::put('/sancion/actualizar', 'SancionController@update');
        Route::put('/sancion/desactivar', 'SancionController@desactivar');
        Route::put('/sancion/activar', 'SancionController@activar');

        Route::get('/oficial', 'OficialController@index');
        Route::post('/oficial/registrar', 'OficialController@store');
        Route::put('/oficial/actualizar', 'OficialController@update');
        Route::put('/oficial/desactivar', 'OficialController@desactivar');
        Route::put('/oficial/activar', 'OficialController@activar');
        Route::get('/oficial/selectOficial', 'OficialController@selectOficial');

        Route::get('/cadete', 'CadeteController@index');
        Route::post('/cadete/registrar', 'CadeteController@store');
        Route::put('/cadete/actualizar', 'CadeteController@update');
        Route::put('/cadete/desactivar', 'CadeteController@desactivar');
        Route::put('/cadete/activar', 'CadeteController@activar');

        //Route::get('/rol', 'RolController@index');
        //Route::post('/rol/registrar', 'RolController@store');
        //Route::put('/rol/actualizar', 'RolController@update');
        //Route::put('/rol/desactivar', 'RolController@desactivar');
        //Route::put('/rol/activar', 'RolController@activar');
        //Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::resource('/user', 'UserController');

        Route::resource('/rol', 'RolController');
        Route::get('/report/franco-de-honor', 'GenerateReportController@listarFrancoDeHonor');
        Route::get('/report/control-merito-demerito', 'GenerateReportController@controlMeritoDemerito');
    });

    Route::group(['middleware' => ['CadeteDisciplina']], function () {
        //
    });

    Route::prefix('api')->group(function () {
        Route::resource('demeritos', 'Api\DemeritoController');
        Route::resource('meritos', 'Api\MeritoController');
    });
});

//Route::get('/home', 'HomeController@index')->name('home');

