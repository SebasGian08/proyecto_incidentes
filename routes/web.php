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

    Route::group(['prefix' => 'cargo'], function () {
        Route::get('/', 'Auth\CargoController@index')->name('auth.cargo');
        Route::get('/list_all', 'Auth\CargoController@list_all')->name('auth.cargo.list_all');
        Route::get('/partialView/{id}', 'Auth\CargoController@partialView')->name('auth.cargo.create');
        Route::post('/store', 'Auth\CargoController@store')->name('auth.cargo.store');
        Route::post('/delete', 'Auth\CargoController@delete')->name('auth.cargo.delete');
    });


    Route::group(['prefix' => 'principal'], function () {
        Route::get('/', 'Auth\PrincipalController@index')->name('auth.principal');

    });

    Route::group(['prefix' => 'error'], function () {
        Route::get('/', 'Auth\ErrorController@index')->name('auth.error');

    });

    /* TOMA DE PEDIDOS */
    Route::group(['prefix' => 'pedidos'], function () {
        Route::get('', 'Auth\PedidosController@index')->name('auth.pedidos');
        Route::get('listado', 'Auth\PedidosController@verlistado')->name('auth.pedidos.listado');
        Route::get('gestion', 'Auth\PedidosController@gestiondepedidos')->name('auth.pedidos.gestion');
        Route::post('store', 'Auth\PedidosController@store')->name('auth.pedidos.store');
        Route::get('list_all', 'Auth\PedidosController@list_all')->name('auth.pedidos.list_all');
        Route::post('delete', 'Auth\PedidosController@delete')->name('auth.pedidos.delete');
        // NUEVAS RUTAS PARA SEGUIMIENTO 1:1
        Route::get('gestion/list', 'Auth\PedidosController@gestionList')->name('auth.pedidos.gestion_list');
        Route::get('gestion/get', 'Auth\PedidosController@gestionGet')->name('auth.pedidos.gestion_get');
        Route::post('gestion/update', 'Auth\PedidosController@gestionUpdate')->name('auth.pedidos.gestion_update');
        Route::get('{id}/guia', 'Auth\PedidosController@descargarGuia')->name('auth.pedidos.guia');
        Route::post('entregar', 'Auth\PedidosController@entregar')->name('auth.pedidos.entregar');
        // RUTA PARA STOCK ACTUALIZADO
        Route::get('stock-actualizado', 'Auth\PedidosController@stockActualizado')->name('auth.pedidos.stock_actualizado');
        
        // SEGUIMIENTO
        Route::get('seguimiento', 'Auth\PedidosController@seguimiento')->name('auth.pedidos.seguimiento');
    });

    /* GESTION DE producto */
    Route::group(['prefix' => 'productos'], function () {
        Route::get('', 'Auth\ProductosController@index')->name('auth.productos');
        Route::post('/store', 'Auth\ProductosController@store')->name('auth.productos.store');
        Route::get('/list_all', 'Auth\ProductosController@list_all')->name('auth.productos.list_all');
        Route::post('/delete', 'Auth\ProductosController@delete')->name('auth.productos.delete');
        Route::get('/partialView/{id}', 'Auth\ProductosController@partialView')->name('auth.productos.create');
    });

    /* COMPRAS */
    Route::group(['prefix' => 'compras'], function () {

        Route::get('', 'Auth\ComprasController@index')->name('auth.compras');
        Route::get('listado', 'Auth\ComprasController@verlistado')->name('auth.compras.listado');
        Route::get('list_all', 'Auth\ComprasController@list_all')->name('auth.compras.list_all');
        Route::post('store', 'Auth\ComprasController@store')->name('auth.compras.store');
        Route::post('delete', 'Auth\ComprasController@delete')->name('auth.compras.delete');
        Route::get('ver/{id}', 'Auth\ComprasController@verCompra')->name('auth.compras.ver');
        Route::get('kardex/{id_producto}', 'Auth\ComprasController@verKardex')->name('auth.compras.kardex');
        Route::get('edit/{id}', 'Auth\ComprasController@edit')->name('auth.compras.edit');
        Route::put('update/{id}', 'Auth\ComprasController@update')->name('auth.compras.update');
        Route::post('delete', 'Auth\ComprasController@delete')->name('auth.compras.delete');

    });

    /* MOVIMIENTOS */
    Route::group(['prefix' => 'movimientos'], function () {

        // Listado / Reporte
        Route::get('/', 'Auth\MovimientosController@index')->name('auth.movimientos');
        Route::get('/list_all', 'Auth\MovimientosController@list_all')->name('auth.movimientos.list_all');

        // Crear Movimiento
        Route::get('/create', 'Auth\MovimientosController@create')->name('auth.movimientos.create');
        Route::post('/store', 'Auth\MovimientosController@store')->name('auth.movimientos.store');

        // Crear Devolución
        Route::get('/devoluciones/create', 'Auth\MovimientosController@devolucionesCreate')->name('auth.devoluciones.create');
        Route::post('/devoluciones/store', 'Auth\MovimientosController@devolucionesStore')->name('auth.devoluciones.store');

        // Crear Ajuste de Cuadre
        Route::get('/ajustes/create', 'Auth\MovimientosController@ajustesCreate')->name('auth.ajustes.create');
        Route::post('/ajustes/store', 'Auth\MovimientosController@ajustesStore')->name('auth.ajustes.store');

    });





    /* MARCAS */
    Route::group(['prefix' => 'marcas'], function () {

        Route::get('', 'Auth\MarcasController@index')->name('auth.marcas');

        Route::get('/list_all', 'Auth\MarcasController@list')->name('auth.marcas.list_all');

        Route::post('/store', 'Auth\MarcasController@store')->name('auth.marcas.store');

        Route::post('/delete', 'Auth\MarcasController@delete')->name('auth.marcas.delete');

        Route::get('/partialView/{id}', 'Auth\MarcasController@partialView')->name('auth.marcas.partial');
    });


    Route::group(['prefix' => 'proveedores'], function () {
        Route::get('', 'Auth\ProveedoresController@index')->name('auth.proveedores');
        Route::get('/list_all', 'Auth\ProveedoresController@list')->name('auth.proveedores.list_all');
        Route::post('/store', 'Auth\ProveedoresController@store')->name('auth.proveedores.store');
        Route::post('/delete', 'Auth\ProveedoresController@delete')->name('auth.proveedores.delete');
        Route::get('/partialView/{id}', 'Auth\ProveedoresController@partialView')->name('auth.proveedores.create');
    });

    Route::group(['prefix'=>'profiles'],function(){
        Route::get('','Auth\ProfilesController@index')->name('auth.profiles');
        Route::get('/list_all','Auth\ProfilesController@list_all')->name('auth.profiles.list_all');
        Route::post('/store','Auth\ProfilesController@store')->name('auth.profiles.store');
        Route::post('/delete','Auth\ProfilesController@delete')->name('auth.profiles.delete');
        Route::get('/partialView/{id}','Auth\ProfilesController@partialView')->name('auth.profiles.create');
    });

    Route::group(['prefix' => 'clientes'], function () {
        Route::get('', 'Auth\ClientesController@index')->name('auth.clientes');
        Route::get('/list_all', 'Auth\ClientesController@list')->name('auth.clientes.list_all');
        Route::post('/store', 'Auth\ClientesController@store')->name('auth.clientes.store');
        Route::post('/delete', 'Auth\ClientesController@delete')->name('auth.clientes.delete');
        Route::get('/partialView/{id}', 'Auth\ClientesController@partialView')->name('auth.clientes.create');
        Route::get('/search', 'Auth\ClientesController@search')->name('auth.clientes.search');
    });

    Route::group(['prefix' => 'motorizado'], function(){
        Route::get('', 'Auth\MotorizadoController@index')->name('auth.pedidos.motorizado');
        Route::get('list', 'Auth\MotorizadoController@list')->name('auth.motorizado.list');
        Route::get('get', 'Auth\MotorizadoController@get')->name('auth.pedidos.motorizado.get');
        Route::post('update', 'Auth\MotorizadoController@update')->name('auth.pedidos.motorizado.update');
        
    });



    Route::group(['prefix' => 'reportes'], function () {

        Route::get('/', 'Auth\ReportesController@index')
            ->name('auth.reportes');

        Route::get('/rotacion', 'Auth\ReportesController@rotacionInventario')
            ->name('auth.reportes.rotacion');

        Route::get('/stock-critico', 'Auth\ReportesController@stockCritico')
            ->name('auth.reportes.stock');

        Route::get('/kardex', 'Auth\ReportesController@kardexValorizado')
            ->name('auth.reportes.kardex');

        Route::get('/margen', 'Auth\ReportesController@margenProducto')
            ->name('auth.reportes.margen');

        Route::get('/excel/{tipo}', 'Auth\ReportesController@exportarExcel')
            ->name('auth.reportes.excel');
    });








});

//Pagina principal
Route::post('/home/store', 'App\HomeController@store')->name('home.store');
Route::get('/registro', 'App\RegistroController@index')->name('app.registro.index');
Route::post('/registro/store', 'App\RegistroController@store')->name('registro.store');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
    Route::post('login', 'Auth\LoginController@login')->name('auth.login.store'); // Ruta para el inicio de sesi��n
    Route::post('login', 'Auth\LoginController@login')->name('auth.login.post');
    Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');

    Route::get('/changePassword/partialView', 'Auth\LoginController@partialView_change_password')->name('auth.login.partialView_change_password');
    Route::post('/changePassword', 'Auth\LoginController@change_password')->name('auth.login.change_password');

    /*Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');*/
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');