<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    protected $fillable = ['fecha','monto','descripcion','tipo','estado'];
}
