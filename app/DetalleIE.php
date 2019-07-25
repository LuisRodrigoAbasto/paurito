<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIE extends Model
{
    protected $primaryKey =['idIE','idCuenta'];    
    public $incrementing = false;
    protected $fillable = ['idIE','idCuenta','debe','haber','descripcionD','estado'];
}
