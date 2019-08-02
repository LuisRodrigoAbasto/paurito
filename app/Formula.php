<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
   // protected $table = 'formulas';
   // protected $primaryKey = 'id';
    protected $fillable = ['nombre','cantidad','estado'];
}
