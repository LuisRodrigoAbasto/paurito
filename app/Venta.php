<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cuenta;
use App\DetalleVenta;
use App\Producto;

class Venta extends Model
{
    public function cuenta()
    {
        return $this->belongsTo('App\Cuenta', 'idCliente');
    }
    public function detalle_venta()
    {
        return $this->hasMany('App\DetalleVenta', 'idVenta');
    }
}
