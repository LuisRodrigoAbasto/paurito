<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends ApiController
{
    public function index(){
        $data=Menu::select('path','nombre','icono')
        // ->where('id',3)
        ->get();
        return $this->responseOk($data, 'Datos Recuperados Correctamente');
    }

    public function sidebar(){
        $data=Menu::select('path','component',
        DB::raw('(path) as name'))
        // ->where('id',1)
        ->get();
        return $this->responseError($data, 'Datos Recuperados Correctamente');
    }
}
