<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{    
    public function producto()
    {
        return $this->belongsTo('App\producto', 'idProducto');
    }
}
