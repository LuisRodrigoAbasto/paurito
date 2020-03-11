<?php
namespace App\Lib;

use App\Compra;
use App\Egreso;
use App\Ingreso;
use App\Venta;

class Libreria
{

    public function registro_maximo()
    {
        $venta = Venta::max('registro');
        $ingreso = Ingreso::max('registro');
        $egreso = Egreso::max('registro');
        $compra = Compra::max('registro');

        $max = 0;
        if ($venta > $max) {
            $max = $venta;
        }
        if ($compra > $max) {
            $max = $compra;
        }
        if ($ingreso > $max) {
            $max = $ingreso;
        }
        if ($egreso > $max) {
            $max = $ingreso;
        }
        return $max;
    }
}
