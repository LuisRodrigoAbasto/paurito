<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    public function producto()
    {
        return $this->belongsTo('App\producto', 'idProducto');
    }
    
}
