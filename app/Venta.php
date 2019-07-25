<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['idCliente','idFormula','fecha','pago','cantidad','montoVenta','descripcion','estado'];

    public function cuenta(){
        return $this->belongsTo('App\Cuenta');
    }

    public function detalle_ventas()
    {
        return $this->hasMany('App\DetalleVenta');
    }
}
