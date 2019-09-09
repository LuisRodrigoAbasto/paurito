<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEgreso extends Model
{
    protected $table="detalle_egresos";
    protected $fillable = ['idEgreso','idCuenta','orden','debe','haber','descripcionD','estado'];
    public function egreso()
    {
        return $this->belongsTo('App\Egreso', 'idEgreso');
    }

    public function cuenta()
    {
        return $this->belongsTo('App\Cuenta', 'idCuenta');
    }
}
