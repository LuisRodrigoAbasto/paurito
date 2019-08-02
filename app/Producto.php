<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre','stock','unidad','codigo','referencia','estado'];
    
    // public function detalle_ventas()
    // {
    //     return $this->hasMany('App\DetalleVenta');
    // }
}
