<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Producto;

class DetalleFormula extends Model
{
   protected $table = 'detalle_formulas';
  // protected $primaryKey =['idFormula','idProducto'];    
  // public $incrementing = false;
    protected $fillable = ['idFormula','idProducto','orden','cantidad','estado'];

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'idProducto');
    }
}
