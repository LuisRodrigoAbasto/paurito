<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = ['factura','registro','idProveedor','fecha','pago','cantidad','montoCompra','descripcion','tipo','estado'];
}
