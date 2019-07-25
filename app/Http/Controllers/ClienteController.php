<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Persona;

class ClienteController extends Controller
{
    
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $personas=Persona::where('tipo','=','1')->orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
        }
        else{
            $personas = Persona::where('tipo','=','1')->where($criterio, 'like','%'.$buscar.'%')->orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
        }
     
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
        
    }
    public function selectCliente(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $clientes = Persona::where('estado','=','1')
        ->where('tipo','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->orwhere('apellido','like','%'.$filtro.'%')
        ->select('id',DB::raw("concat(nombre,' ',apellido) as cliente"))        
        ->orderBy('nombre','asc')
        ->get();
        return ['clientes'=>$clientes];
        
    }
    
    public function store(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $personas = new Persona();
        $personas->nombre = $request->nombre;
        $personas->apellido = $request->apellido;
        $personas->telefono = $request->telefono;
        $personas->empresa = $request->empresa;
        $personas->direccion = $request->direccion;
        $personas->tipo = '1';
        $personas->estado = '1';
        $personas->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $personas = Persona::findOrFail($request->id);
        $personas->nombre = $request->nombre;
        $personas->apellido = $request->apellido;
        $personas->telefono = $request->telefono;
        $personas->empresa = $request->empresa;
        $personas->direccion = $request->direccion;
        $personas->tipo = '1';
        $personas->estado = '1';
        $personas->save();
    }
    
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $personas = Persona::findOrFail($request->id);
        $personas->estado = '0';
        $personas->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $personas = Persona::findOrFail($request->id);
        $personas->estado = '1';
        $personas->save();
    }
       
}
