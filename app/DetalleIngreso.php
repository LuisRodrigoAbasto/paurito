<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table="detalle_ingresos";
    protected $fillable = ['idIngreso','idCuenta','orden','debe','haber','descripcionD','estado'];
}
