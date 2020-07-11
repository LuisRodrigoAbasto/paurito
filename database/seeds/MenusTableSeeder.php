<?php

use Illuminate\Database\Seeder;
use App\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'nombre'=>'Dashboard',
            'icono'=>'nav-icon icon-bag',
            'path'=>'dashboard',
            'component'=>'DashboardComponent'
        ]);
        Menu::create([
            'nombre'=>'Formula',
            'icono'=>'nav-icon icon-grid',
            'path'=>'formula',
            'component'=>'FormulaComponent'
        ]);
        Menu::create([
            'nombre'=>'Producto',
            'icono'=>'nav-icon fa fa-product-hunt',
            'path'=>'producto',
            'component'=>'ProductoComponent'
        ]);

        Menu::create([
            'nombre'=>'Venta',
            'icono'=>'nav-icon icon-basket-loaded',
            'path'=>'venta',
            'component'=>'VentaComponent'
        ]);
        Menu::create([
            'nombre'=>'Compra',
            'icono'=>'nav-icon icon-handbag',
            'path'=>'compra',
            'component'=>'CompraComponent'
        ]);
        Menu::create([
            'nombre'=>'Ingreso',
            'icono'=>'nav-icon fa fa-money',
            'path'=>'ingreso',
            'component'=>'IngresoComponent'
        ]);
        Menu::create([
            'nombre'=>'Egreso',
            'icono'=>'nav-icon fa fa-money',
            'path'=>'egreso',
            'component'=>'EgresoComponent'
        ]);


        Menu::create([
            'nombre'=>'Cuenta',
            'icono'=>'nav-icon icon-briefcase',
            'path'=>'cuenta',
            'component'=>'CuentaComponent'
        ]);

        Menu::create([
            'nombre'=>'Balance General',
            'icono'=>'nav-icon icon-chart',
            'path'=>'balance_general',
            'component'=>'/'
        ]);
        Menu::create([
            'nombre'=>'Estado de Resultado',
            'icono'=>'nav-icon icon-chart',
            'path'=>'estado_resultado',
            'component'=>null
        ]);
        Menu::create([
            'nombre'=>'Usuario',
            'icono'=>'nav-icon icon-user',
            'path'=>'usuario',
            'component'=>null
        ]);
    }
}
