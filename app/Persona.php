<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre','apellido','telefono','empresa','direccion','tipo','estado'];
}
