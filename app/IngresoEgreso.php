<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngresoEgreso extends Model
{
    protected $fillable = ['fecha','monto','descripcion','tipo','estado'];
}
