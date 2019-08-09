<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = ['factura','registro','fecha','monto','descripcion','tipo','estado'];
}
