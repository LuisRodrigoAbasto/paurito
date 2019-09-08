<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $fillable = ['idCuenta','nombre','telefono','empresa','direccion','nivel','tipo','nivel1','nivel2','nivel3','nivel4','estado'];

    public function cuenta()
    {
        return $this->belongsTo('App\Cuenta', 'idCuenta');
    }
}
