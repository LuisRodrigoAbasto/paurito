<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $primaryKey =['idCompra','idProducto'];    
    public $incrementing = false;
    protected $fillable = ['idCompra','idProducto','cantidad','precio','estado'];
    
  //  public $timestamps = false;
    public function compra()
    {
        return $this->belongsTo('App\Compra', 'idCompra');
    }
    public function producto()
    {
        return $this->belongsTo('App\producto', 'idProducto');
    }
}
