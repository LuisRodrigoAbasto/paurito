<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIE extends Model
{
    // protected $primaryKey =['idIE','idCuenta'];    
    // public $incrementing = false;
    protected $table="detalle_ingresos_egresos";
    protected $fillable = ['idIE','idCuenta','orden','debe','haber','descripcionD','estado'];
}
