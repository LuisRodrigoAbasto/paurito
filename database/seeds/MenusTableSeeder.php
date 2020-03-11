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
            'nombre'=>'Almacen',
            'icono'=>'nav-icon icon-bag',
            'url'=>'',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Formula',
            'icono'=>'nav-icon icon-grid',
            'url'=>'formula',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Producto',
            'icono'=>'nav-icon fa fa-product-hunt',
            'url'=>'Producto',
            'padre_id'=>null
        ]);

        Menu::create([
            'nombre'=>'Venta',
            'icono'=>'nav-icon icon-basket-loaded',
            'url'=>'venta',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Compra',
            'icono'=>'nav-icon icon-handbag',
            'url'=>'compra',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Ingreso',
            'icono'=>'nav-icon fa fa-money',
            'url'=>'ingreso',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Egreso',
            'icono'=>'nav-icon fa fa-money',
            'url'=>'egreso',
            'padre_id'=>null
        ]);


        Menu::create([
            'nombre'=>'Cuenta',
            'icono'=>'nav-icon icon-briefcase',
            'url'=>'cuenta',
            'padre_id'=>null
        ]);

        Menu::create([
            'nombre'=>'Balance Generak',
            'icono'=>'nav-icon icon-chart',
            'url'=>'balance_general',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Estado de Resultado',
            'icono'=>'nav-icon icon-chart',
            'url'=>'estado_resultado',
            'padre_id'=>null
        ]);
        Menu::create([
            'nombre'=>'Usuario',
            'icono'=>'nav-icon icon-user',
            'url'=>'usuario',
            'padre_id'=>null
        ]);
    }
}
