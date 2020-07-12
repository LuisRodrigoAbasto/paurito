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

Route::get('/api/menu', 'Api\MenuController@index');


Route::get('/api/formula', 'Api\FormulaController@index');
Route::post('/api/formula/registrar', 'Api\FormulaController@store');
Route::put('/api/formula/actualizar', 'Api\FormulaController@update');
Route::put('/api/formula/desactivar', 'Api\FormulaController@desactivar');
Route::put('/api/formula/activar', 'Api\FormulaController@activar');
Route::get('/api/formula/selectFormula', 'Api\FormulaController@selectFormula');
Route::get('/api/formula/listarFormula', 'Api\FormulaController@listarFormula');
Route::get('/api/formula/pdf/formula_{id}','FormulaController@pdf')->name('formula_pdf');
Route::get('/api/formula/imprimir/formula_{id}','FormulaController@imprimir')->name('formula_imprimir');

Route::get('/api/producto', 'Api\ProductoController@index');
Route::get('/api/producto/get/{id}', 'Api\ProductoController@get');
Route::post('/api/producto', 'Api\ProductoController@store');
Route::put('/api/producto', 'Api\ProductoController@update');
Route::put('/api/producto/desactivar', 'Api\ProductoController@desactivar');
Route::put('/api/producto/activar', 'Api\ProductoController@activar');
Route::get('/api/producto/selectProducto', 'Api\ProductoController@selectProducto');
Route::get('/api/producto/listarProducto', 'Api\ProductoController@listarProducto');
Route::get('/api/producto/listarPdf', 'ProductoController@listarPdf')->name('productos_pdf');
Route::get('/api/producto/imprimir', 'ProductoController@listarImprimir')->name('productos_imprimir');
Route::get('/api/producto/notificaciones', 'ProductoController@notificaciones');

Route::get('/api/venta', 'Api\VentaController@index');
Route::post('/api/venta/registrar', 'Api\VentaController@store');
Route::put('/api/venta/actualizar', 'Api\VentaController@update');
Route::put('/api/venta/desactivar', 'Api\VentaController@desactivar');
Route::put('/api/venta/activar', 'Api\VentaController@activar');
Route::get('/api/venta/listarVentas', 'Api\VentaController@listarVentas');
Route::get('/api/venta/pdf/venta_{id}','VentaController@pdf')->name('venta_pdf');
Route::get('/api/venta/imprimir/venta_{id}','VentaController@imprimir')->name('venta_imprimir');

Route::get('/api/compra', 'Api\CompraController@index');
Route::post('/api/compra/registrar', 'Api\CompraController@store');
Route::put('/api/compra/actualizar', 'Api\CompraController@update');
Route::put('/api/compra/desactivar', 'Api\CompraController@desactivar');
Route::put('/api/compra/activar', 'Api\CompraController@activar');
Route::get('/api/compra/listarCompras', 'Api\CompraController@listarCompras');
Route::get('/api/compra/pdf/compra_{id}','CompraController@pdf')->name('compra_pdf');
Route::get('/api/compra/imprimir/compra_{id}','CompraController@imprimir')->name('compra_imprimir');

Route::get('/api/cuenta', 'Api\CuentaController@index');
Route::post('/api/cuenta/registrar', 'Api\CuentaController@store');
Route::put('/api/cuenta/actualizar', 'Api\CuentaController@update');
Route::put('/api/cuenta/desactivar', 'Api\CuentaController@desactivar');
Route::put('/api/cuenta/activar', 'Api\CuentaController@activar');
Route::get('/api/cuenta/cuenta', 'Api\CuentaController@cuenta');
Route::get('/api/cuenta/selectCuenta', 'Api\CuentaController@selectCuenta');
Route::get('/api/cuenta/buscarCuenta', 'Api\CuentaController@buscarCuenta');
Route::get('/api/cuenta/listarCuenta', 'Api\CuentaController@listarCuenta');
Route::get('/api/cuenta/listarPdf', 'Api\CuentaController@listarPdf')->name('cuentas_pdf');
Route::get('/api/cuenta/imprimir', 'Api\CuentaController@listarImprimir')->name('cuentas_imprimir');

Route::get('/api/plancuenta', 'Api\PlanCuentaController@index');
Route::post('/api/plancuenta/registrar', 'Api\PlanCuentaController@store');
Route::put('/api/plancuenta/actualizar', 'Api\PlanCuentaController@update');
Route::put('/api/plancuenta/desactivar', 'Api\PlanCuentaController@desactivar');
Route::put('/api/plancuenta/activar', 'Api\PlanCuentaController@activar');

Route::get('/api/ingreso', 'Api\IngresoController@index');
Route::post('/api/ingreso/registrar', 'Api\IngresoController@store');
Route::put('/api/ingreso/actualizar', 'Api\IngresoController@update');
Route::put('/api/ingreso/desactivar', 'Api\IngresoController@desactivar');
Route::put('/api/ingreso/activar', 'Api\IngresoController@activar');
Route::get('/api/ingreso/listarIngreso', 'Api\IngresoController@listarIngreso');
Route::get('/api/ingreso/pdf/ingreso_{id}','IngresoController@pdf')->name('ingreso_pdf');
Route::get('/api/ingreso/imprimir/ingreso_{id}','IngresoController@imprimir')->name('ingreso_imprimir');

Route::get('/api/egreso', 'Api\EgresoController@index');
Route::post('/api/egreso/registrar', 'Api\EgresoController@store');
Route::put('/api/egreso/actualizar', 'Api\EgresoController@update');
Route::put('/api/egreso/desactivar', 'Api\EgresoController@desactivar');
Route::put('/api/egreso/activar', 'Api\EgresoController@activar');
Route::get('/api/egreso/listarEgreso', 'Api\EgresoController@listarEgreso');
Route::get('/api/egreso/pdf/egreso_{id}','EgresoController@pdf')->name('egreso_pdf');
Route::get('/api/egreso/imprimir/egreso_{id}','EgresoController@imprimir')->name('egreso_imprimir');

Route::get('/api/balanceGeneral', 'Api\BalanceGeneralController@index');
Route::get('/api/estadoResultado', 'Api\EstadoResultadoController@index');