<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table="detalle_ingresos";
    protected $fillable = ['idIngreso','idCuenta','orden','debe','haber','descripcionD','estado'];
    public function ingreso()
    {
        return $this->belongsTo('App\Ingreso', 'idIngreso');
    }

    public function cuenta()
    {
        return $this->belongsTo('App\Cuenta', 'idCuenta');
    }
}
