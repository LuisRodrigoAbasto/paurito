<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEgreso extends Model
{
    public function cuenta()
    {
        return $this->belongsTo('App\Cuenta', 'idCuenta');
    }
}
