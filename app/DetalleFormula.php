<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleFormula extends Model
{
   protected $table = 'detalle_formulas';
  // protected $primaryKey =['idFormula','idProducto'];    
  // public $incrementing = false;
    protected $fillable = ['idFormula','idProducto','orden','cantidad','estado'];
    
  //  public $timestamps = false;
}
