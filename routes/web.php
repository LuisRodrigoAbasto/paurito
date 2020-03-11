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
Route::get('/', 'PauritoController@index');

Route::get('/dashboard_controller','Api\DashboardController@index');
   
Route::get('/formula', 'FormulaController@index');
Route::post('/formula/registrar', 'FormulaController@store');
Route::put('/formula/actualizar', 'FormulaController@update');
Route::put('/formula/desactivar', 'FormulaController@desactivar');
Route::put('/formula/activar', 'FormulaController@activar');
Route::get('/formula/selectFormula', 'FormulaController@selectFormula');
Route::get('/formula/listarFormula', 'FormulaController@listarFormula');
Route::get('/formula/pdf/formula_{id}','FormulaController@pdf')->name('formula_pdf');
Route::get('/formula/imprimir/formula_{id}','FormulaController@imprimir')->name('formula_imprimir');

Route::get('/producto', 'ProductoController@index');
Route::post('/producto/registrar', 'ProductoController@store');
Route::put('/producto/actualizar', 'ProductoController@update');
Route::put('/producto/desactivar', 'ProductoController@desactivar');
Route::put('/producto/activar', 'ProductoController@activar');
Route::get('/producto/selectProducto', 'ProductoController@selectProducto');
Route::get('/producto/listarProducto', 'Api\ProductoController@listarProducto');
Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');
Route::get('/producto/imprimir', 'ProductoController@listarImprimir')->name('productos_imprimir');
Route::get('/producto/notificaciones', 'ProductoController@notificaciones');

Route::get('/venta', 'VentaController@index');
Route::post('/venta/registrar', 'VentaController@store');
Route::put('/venta/actualizar', 'VentaController@update');
Route::put('/venta/desactivar', 'VentaController@desactivar');
Route::put('/venta/activar', 'VentaController@activar');
Route::get('/venta/listarVentas', 'VentaController@listarVentas');
Route::get('/venta/pdf/venta_{id}','VentaController@pdf')->name('venta_pdf');
Route::get('/venta/imprimir/venta_{id}','VentaController@imprimir')->name('venta_imprimir');

Route::get('/compra', 'Api\CompraController@index');
Route::post('/compra/registrar', 'CompraController@store');
Route::put('/compra/actualizar', 'CompraController@update');
Route::put('/compra/desactivar', 'CompraController@desactivar');
Route::put('/compra/activar', 'CompraController@activar');
Route::get('/compra/listarCompras', 'CompraController@listarCompras');
Route::get('/compra/pdf/compra_{id}','CompraController@pdf')->name('compra_pdf');
Route::get('/compra/imprimir/compra_{id}','CompraController@imprimir')->name('compra_imprimir');

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
Route::get('/cuenta/imprimir', 'CuentaController@listarImprimir')->name('cuentas_imprimir');

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
Route::get('/ingreso/listarIngreso', 'IngresoController@listarIngreso');
Route::get('/ingreso/pdf/ingreso_{id}','IngresoController@pdf')->name('ingreso_pdf');
Route::get('/ingreso/imprimir/ingreso_{id}','IngresoController@imprimir')->name('ingreso_imprimir');

Route::get('/egreso', 'EgresoController@index');
Route::post('/egreso/registrar', 'EgresoController@store');
Route::put('/egreso/actualizar', 'EgresoController@update');
Route::put('/egreso/desactivar', 'EgresoController@desactivar');
Route::put('/egreso/activar', 'EgresoController@activar');
Route::get('/egreso/listarEgreso', 'EgresoController@listarEgreso');
Route::get('/egreso/pdf/egreso_{id}','EgresoController@pdf')->name('egreso_pdf');
Route::get('/egreso/imprimir/egreso_{id}','EgresoController@imprimir')->name('egreso_imprimir');

Route::get('/balanceGeneral', 'BalanceGeneralController@index');
Route::get('/estadoResultado', 'EstadoResultadoController@index');