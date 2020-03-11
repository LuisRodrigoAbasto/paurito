<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Producto;

class DetalleFormula extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\Producto', 'idProducto');
    }
}
