<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable=[
        'nombre',
        'stock',
        'ref_stock',
        'unidad',
        'ref_unidad',
        'estado'
    ];
}
