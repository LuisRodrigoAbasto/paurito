<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    // protected $primaryKey =['idVenta','idProducto'];    
    // public $incrementing = false;
    // protected $keyType = 'int';
    // protected $guarded = ['idVenta','idProducto'];
    protected $fillable = ['idVenta','idProducto','orden','cantidad','precio','descripcionD','estado'];
    
    public function producto()
    {
        return $this->belongsTo('App\producto', 'idProducto');
    }
    
}
