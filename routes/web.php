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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(['middleware'=>['auth']],function(){
    // Route::get('/',function(){
    //     return view('contenido/contenido');
    // })->name('main');
    Route::get('/', 'HomeController@index')->name('home');

   Route::get('/dashboard','DashboardController');

   Route::get('/cliente', 'ClienteController@index');
   Route::post('/cliente/registrar', 'ClienteController@store');
   Route::put('/cliente/actualizar', 'ClienteController@update');
   Route::put('/cliente/desactivar', 'ClienteController@desactivar');
   Route::put('/cliente/activar', 'ClienteController@activar');
   Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
   
   Route::get('/proveedor', 'ProveedorController@index');
   Route::post('/proveedor/registrar', 'ProveedorController@store');
   Route::put('/proveedor/actualizar', 'ProveedorController@update');
   Route::put('/proveedor/desactivar', 'ProveedorController@desactivar');
   Route::put('/proveedor/activar', 'ProveedorController@activar');
   Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');
   
   Route::get('/formula', 'FormulaController@index');
   Route::post('/formula/registrar', 'FormulaController@store');
   Route::put('/formula/actualizar', 'FormulaController@update');
   Route::put('/formula/desactivar', 'FormulaController@desactivar');
   Route::put('/formula/activar', 'FormulaController@activar');
   Route::get('/formula/selectFormula', 'FormulaController@selectFormula');
   Route::get('/formula/listarFormula', 'FormulaController@listarFormula');
   
   Route::get('/producto', 'ProductoController@index');
   Route::post('/producto/registrar', 'ProductoController@store');
   Route::put('/producto/actualizar', 'ProductoController@update');
   Route::put('/producto/desactivar', 'ProductoController@desactivar');
   Route::put('/producto/activar', 'ProductoController@activar');
   Route::get('/producto/selectProducto', 'ProductoController@selectProducto');
   Route::get('/producto/listarProducto', 'ProductoController@listarProducto');
   Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');
   Route::get('/producto/notificaciones', 'ProductoController@notificaciones');
   
   Route::get('/venta', 'VentaController@index');
   Route::post('/venta/registrar', 'VentaController@store');
   Route::put('/venta/actualizar', 'VentaController@update');
   Route::put('/venta/desactivar', 'VentaController@desactivar');
   Route::put('/venta/activar', 'VentaController@activar');
   Route::get('/venta/obtenerVenta', 'VentaController@obtenerVenta');
   Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
   Route::get('/venta/listarVentas', 'VentaController@listarVentas');
   Route::get('/venta/pdf/venta_{id}','VentaController@pdf')->name('venta_pdf');
   
   Route::get('/compra', 'CompraController@index');
   Route::post('/compra/registrar', 'CompraController@store');
   Route::put('/compra/actualizar', 'CompraController@update');
   Route::put('/compra/desactivar', 'CompraController@desactivar');
   Route::put('/compra/activar', 'CompraController@activar');
   Route::get('/compra/listarCompras', 'CompraController@listarCompras');
   
   Route::get('/cuenta', 'CuentaController@index');
   Route::post('/cuenta/registrar', 'CuentaController@store');
   Route::put('/cuenta/actualizar', 'CuentaController@update');
   Route::put('/cuenta/desactivar', 'CuentaController@desactivar');
   Route::put('/cuenta/activar', 'CuentaController@activar');
   Route::get('/cuenta/cuenta', 'CuentaController@cuenta');
   Route::get('/cuenta/selectCuenta', 'CuentaController@selectCuenta');
   Route::get('/cuenta/buscarCuenta', 'CuentaController@buscarCuenta');
   Route::get('/cuenta/listarCuenta', 'CuentaController@listarCuenta');
   Route::get('/cuenta/listarPdf', 'CuentaController@listarPdf')->name('cuentas_pdf');

   Route::get('/plancuenta', 'PlanCuentaController@index');
   Route::post('/plancuenta/registrar', 'PlanCuentaController@store');
   Route::put('/plancuenta/actualizar', 'PlanCuentaController@update');
   Route::put('/plancuenta/desactivar', 'PlanCuentaController@desactivar');
   Route::put('/plancuenta/activar', 'PlanCuentaController@activar');

   Route::get('/ingreso', 'IngresoController@index');
   Route::post('/ingreso/registrar', 'IngresoController@store');
   Route::put('/ingreso/actualizar', 'IngresoController@update');
   Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
   Route::put('/ingreso/activar', 'IngresoController@activar');
   Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
   Route::get('/ingreso/listarIngreso', 'IngresoController@listarIngreso');

   Route::get('/egreso', 'EgresoController@index');
   Route::post('/egreso/registrar', 'EgresoController@store');
   Route::put('/egreso/actualizar', 'EgresoController@update');
   Route::put('/egreso/desactivar', 'EgresoController@desactivar');
   Route::put('/egreso/activar', 'EgresoController@activar');
   Route::get('/egreso/obtenerDetalles', 'EgresoController@obtenerDetalles');
   Route::get('/egreso/listarEgreso', 'EgresoController@listarEgreso');

   Route::get('/cuenta/balanceGeneral', 'CuentaController@balanceGeneral');

   });
   
Auth::routes();


