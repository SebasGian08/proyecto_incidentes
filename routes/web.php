<?php

App::setLocale('es');


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
        Route::get('/incidentes/gestion', 'Auth\IncidenteController@gestion')->name('incidentes.gestion');
        Route::get('/mis-incidentes', 'Auth\IncidenteController@misIncidentes')->name('incidentes.mis');
        Route::post('/calificar', 'Auth\IncidenteController@calificar')->name('incidentes.calificar');
        Route::get('/notification', 'Auth\IncidenteController@notification')->name('incidentes.notification');
        Route::post('/notificaciones/leidas', 'Auth\IncidenteController@marcarLeidas')->name('incidentes.leidas');
    });

    Route::group(['prefix'=>'dashboard'], function() {
        Route::get('/', 'Auth\DashboardController@index')->name('auth.dashboard');
    });
    
    Route::group(['prefix'=>'activos'], function() {
        Route::get('/', 'Auth\ActivosController@index')->name('auth.activos');
        Route::get('/listar', 'Auth\ActivosController@listar')->name('activos.listar');
        Route::post('/store', 'Auth\ActivosController@store')->name('activos.store');
        Route::get('/get/{id}', 'Auth\ActivosController@get')->name('activos.get');
        Route::post('/update', 'Auth\ActivosController@update')->name('activos.update');
        Route::post('/delete', 'Auth\ActivosController@delete')->name('activos.delete');
    });

    Route::group(['prefix'=>'tipo-activos'], function() {
        Route::get('/tipo-activos', 'Auth\TipoActivoController@index')->name('auth.tipo_activos');
        Route::get('/listar', 'Auth\TipoActivoController@listar')->name('tipo_activos.listar');
        Route::post('/store', 'Auth\TipoActivoController@store')->name('tipo_activos.store');
        Route::get('/get/{id}', 'Auth\TipoActivoController@get')->name('tipo_activos.get');
        Route::post('/update', 'Auth\TipoActivoController@update')->name('tipo_activos.update');
        Route::post('/delete', 'Auth\TipoActivoController@delete')->name('tipo_activos.delete');
    });

    Route::group(['prefix'=>'severidad'], function() {
        Route::get('/severidad', 'Auth\SeveridadController@index')->name('auth.severidad');
        Route::get('/listar', 'Auth\SeveridadController@listar')->name('severidad.listar');
        Route::post('/store', 'Auth\SeveridadController@store')->name('severidad.store');
        Route::get('/get/{id}', 'Auth\SeveridadController@get')->name('severidad.get');
        Route::post('/update', 'Auth\SeveridadController@update')->name('severidad.update');
        Route::post('/delete', 'Auth\SeveridadController@delete')->name('severidad.delete');
    });
    
    Route::group(['prefix'=>'criticidad'], function() {
        Route::get('/criticidad', 'Auth\CriticidadController@index')->name('auth.criticidad');
        Route::get('/listar', 'Auth\CriticidadController@listar')->name('criticidad.listar');
        Route::post('/store', 'Auth\CriticidadController@store')->name('criticidad.store');
        Route::get('/get/{id}', 'Auth\CriticidadController@get')->name('criticidad.get');
        Route::post('/update', 'Auth\CriticidadController@update')->name('criticidad.update');
        Route::post('/delete', 'Auth\CriticidadController@delete')->name('criticidad.delete');
    });

    Route::group(['prefix'=>'ubicacion-fisica'], function() {
        Route::get('/ubicacion-fisica', 'Auth\UbicacionFisicaController@index')->name('auth.ubicacion_fisica');
        Route::get('/listar', 'Auth\UbicacionFisicaController@listar')->name('ubicacion-fisica.listar');
        Route::post('/store', 'Auth\UbicacionFisicaController@store')->name('ubicacion-fisica.store');
        Route::get('/get/{id}', 'Auth\UbicacionFisicaController@get')->name('ubicacion-fisica.get');
        Route::post('/update', 'Auth\UbicacionFisicaController@update')->name('ubicacion-fisica.update');
        Route::post('/delete', 'Auth\UbicacionFisicaController@delete')->name('ubicacion-fisica.delete');
    });

    Route::group(['prefix'=>'confidencialidad'], function() {
        Route::get('/confidencialidad', 'Auth\ConfidencialidadController@index')->name('auth.confidencialidad');
        Route::get('/listar', 'Auth\ConfidencialidadController@listar')->name('confidencialidad.listar');
        Route::post('/store', 'Auth\ConfidencialidadController@store')->name('confidencialidad.store');
        Route::get('/get/{id}', 'Auth\ConfidencialidadController@get')->name('confidencialidad.get');
        Route::post('/update', 'Auth\ConfidencialidadController@update')->name('confidencialidad.update');
        Route::post('/delete', 'Auth\ConfidencialidadController@delete')->name('confidencialidad.delete');
    });

    Route::group(['prefix'=>'estado_activo'], function() {
        Route::get('/estado_activo', 'Auth\EstadoActivoController@index')->name('auth.estado_activo');
        Route::get('/listar', 'Auth\EstadoActivoController@listar')->name('estado_activo.listar');
        Route::post('/store', 'Auth\EstadoActivoController@store')->name('estado_activo.store');
        Route::get('/get/{id}', 'Auth\EstadoActivoController@get')->name('estado_activo.get');
        Route::post('/update', 'Auth\EstadoActivoController@update')->name('estado_activo.update');
        Route::post('/delete', 'Auth\EstadoActivoController@delete')->name('estado_activo.delete');

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