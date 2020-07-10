<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Menu;

class MenuController extends ApiController
{
    public function index(){
        $data=Menu::all();
        return $this->sendResponse($data, 'Datos Recuperados Correctamente');
    }
}
