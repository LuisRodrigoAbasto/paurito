<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $fillable = ['nombre','telefono','empresa','direccion','tipo','nivel1','nivel2','nivel3','nivel4','estado'];
}
