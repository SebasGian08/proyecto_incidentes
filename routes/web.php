<?php

App::setLocale('es');

Route::get('/', 'App\HomeController@index')->name('index');
Route::get('/loginEmpresa', 'App\HomeController@loginEmpresa')->name('loginEmpresa');
Route::get('/filtro_distritos/{id}', 'App\HomeController@filtro_distritos')->name('filtro_distritos');
Route::get('/offline_alumno/{id}', 'App\LoginAlumnoController@offline');

Route::get('/buscar_reniec/{data}', 'App\LoginEmpresaController@consultar_reniec')->name('buscar_reniec');
Route::get('/buscar_sunat/{data}', 'App\LoginEmpresaController@consultar_sunat')->name('buscar_sunat');

/* ADMINISTRADOR */
Route::get('/home/notification', 'Auth\EmpresaController@notification')->name('auth.home.notification');

Route::group(['prefix' => 'auth', 'middleware' => 'auth:web'], function () {
    Route::get('/home', 'Auth\HomeController@index')->name('auth.index');

    Route::group(['prefix' => 'inicio'], function () {
        Route::get('/', 'Auth\InicioController@index')->name('auth.inicio');
    });


    // SECTION USUARIO
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', 'Auth\UsuariosController@index')->name('auth.usuarios');
        Route::get('/list_all', 'Auth\UsuariosController@list_all')->name('auth.usuarios.list_all');
        Route::post('/store', 'Auth\UsuariosController@store')->name('auth.usuarios.store');
        Route::post('/delete', 'Auth\UsuariosController@delete')->name('auth.usuarios.delete');
        Route::get('/partialView/{id}', 'Auth\UsuariosController@partialView')->name('auth.usuarios.create');
    });
    // END SECTION USUARIO

    Route::group(['prefix' => 'principal'], function () {
        Route::get('/', 'Auth\PrincipalController@index')->name('auth.principal');

    });

    Route::group(['prefix' => 'error'], function () {
        Route::get('/', 'Auth\ErrorController@index')->name('auth.error');

    });

    Route::group(['prefix'=>'profiles'],function(){
        Route::get('','Auth\ProfilesController@index')->name('auth.profiles');
        Route::get('/list_all','Auth\ProfilesController@list_all')->name('auth.profiles.list_all');
        Route::post('/store','Auth\ProfilesController@store')->name('auth.profiles.store');
        Route::post('/delete','Auth\ProfilesController@delete')->name('auth.profiles.delete');
        Route::get('/partialView/{id}','Auth\ProfilesController@partialView')->name('auth.profiles.create');
    });

    Route::group(['prefix'=>'incidentes'], function() {
        Route::post('/store', 'Auth\IncidenteController@store')->name('incidentes.store');
        Route::get('/list_all', 'Auth\IncidenteController@list_all')->name('incidentes.list_all');
        Route::get('/list_filtros', 'Auth\IncidenteController@list_all_filtros')->name('incidentes.list_filtros');
        Route::get('/list_historial/{id}', 'Auth\IncidenteController@list_historial')->name('incidentes.historial');
        Route::post('/agregar_comentario', 'Auth\IncidenteController@agregarComentario')->name('incidentes.agregar_comentario');
        Route::get('/incidentes', 'Auth\IncidenteController@index')->name('incidentes.index');
        Route::get('/get/{id}', 'Auth\IncidenteController@get')->name('incidentes.get');
        Route::post('/update', 'Auth\IncidenteController@update')->name('incidentes.update');
    });


});



//Pagina principal
Route::post('/home/store', 'App\HomeController@store')->name('home.store');
//Route::get('/registro', 'App\RegistroController@index')->name('app.registro.index');
Route::post('/registro/store', 'App\RegistroController@store')->name('registro.store');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
    Route::post('login', 'Auth\LoginController@login')->name('auth.login.store');
    Route::post('login', 'Auth\LoginController@login')->name('auth.login.post');
    Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');

    Route::get('/changePassword/partialView', 'Auth\LoginController@partialView_change_password')->name('auth.login.partialView_change_password');
    Route::post('/changePassword', 'Auth\LoginController@change_password')->name('auth.login.change_password');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');