<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    protected $fillable = ['factura','registro','fecha','monto','descripcion','tipo','estado'];
}
