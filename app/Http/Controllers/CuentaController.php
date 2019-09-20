<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cuenta;

class CuentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        // $criterio = $request->criterio;
        if($buscar=='')
        {
            $cuentas=Cuenta::orderBy('nivel1','asc')
            ->orderBy('nivel2','asc')
            ->orderBy('nivel3','asc')
            ->orderBy('nivel4','asc')
            ->orderBy('nivel5','asc')
            ->paginate(20);
        }
        else{
            $cuentas=Cuenta::where('nombre', 'like','%'.$buscar.'%')
            ->orderBy('nivel1','asc')
            ->orderBy('nivel2','asc')
            ->orderBy('nivel3','asc')
            ->orderBy('nivel4','asc')
            ->orderBy('nivel5','asc')
            ->paginate(20);
        }

        return [
            'pagination' => [
                'total'        => $cuentas->total(),
                'current_page' => $cuentas->currentPage(),
                'per_page'     => $cuentas->perPage(),
                'last_page'    => $cuentas->lastPage(),
                'from'         => $cuentas->firstItem(),
                'to'           => $cuentas->lastItem(),
            ],
            'cuentas' => $cuentas
        ];
    }
    public function buscarCuenta(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idCuenta=$request->idCuenta;
        
        if($idCuenta==0){
        $cuentas = Cuenta::where('nivel','=','1')
        ->count('id');
        }
        else{
            $cuentas = Cuenta::where('idCuenta','=',$idCuenta)
        ->count('id');
            }
            $cuentas++;
        return ['cuentas'=>$cuentas];
    }
    public function listarPdf(Request $request)
    {
        $cuentas=Cuenta::where('estado','=','1')
        ->orderBy('nivel1','asc')
        ->orderBy('nivel2','asc')
        ->orderBy('nivel3','asc')
        ->orderBy('nivel4','asc')
        ->orderBy('nivel5','asc')
        ->get();
        $pdf = \PDF::loadView('cuenta.pdf.index',['cuentas'=>$cuentas]);
        return $pdf->download('cuentas.pdf');
        // return $pdf->stream();
    }
    public function listarImprimir(Request $request)
    {
        $cuentas=Cuenta::where('estado','=','1')
        ->orderBy('nivel1','asc')
        ->orderBy('nivel2','asc')
        ->orderBy('nivel3','asc')
        ->orderBy('nivel4','asc')
        ->orderBy('nivel5','asc')
        ->get();

        // $pdf = App::make('dompdf.wrapper');
        return view('cuenta.imprimir.index',['cuentas'=>$cuentas]);
    }
    
    public function cuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('nivel','<','5')
        ->where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->get();
        return ['cuentas'=>$cuentas];
    }
    public function selectCuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('nivel','=','5')
        ->where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->limit(10)
        ->get();
        return ['cuentas'=>$cuentas];
    }
    public function listarCuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->where('nivel','=','5')
        ->orderBy('nombre','asc')
        ->limit(10)
        ->get();
        return ['cuentas'=>$cuentas];
    }
   
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cuentas = new Cuenta();
        $cuentas->nombre = $request->nombre;
        $cuentas->telefono = $request->telefono;
        $cuentas->empresa = $request->empresa;
        $cuentas->direccion = $request->direccion;
        $cuentas->nivel=$request->nivel;
        $cuentas->idCuenta=$request->idCuenta;

        if($cuentas->idCuenta==0)
        {
            $cuentas->idCuenta=null;
        }
        $cuentas->nivel1 = $request->nivel1;
        $cuentas->nivel2 = $request->nivel2;
        $cuentas->nivel3 = $request->nivel3;
        $cuentas->nivel4 = $request->nivel4;
        $cuentas->nivel5 = $request->nivel5;
        $cuentas->estado = '1';
        $cuentas->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cuentas = Cuenta::findOrFail($request->id);
        $cuentas->nombre = $request->nombre;
        $cuentas->telefono = $request->telefono;
        $cuentas->empresa = $request->empresa;
        $cuentas->direccion = $request->direccion;
        $cuentas->nivel=$request->nivel;
        $cuentas->nivel1 = $request->nivel1;
        $cuentas->nivel2 = $request->nivel2;
        $cuentas->nivel3 = $request->nivel3;
        $cuentas->nivel4 = $request->nivel4;
        $cuentas->nivel5 = $request->nivel5;
        $cuentas->estado = '1';
        $cuentas->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cuentas = Cuenta::findOrFail($request->id);
        $cuentas->estado = '1';
        $cuentas->save();
    }
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cuentas = Cuenta::findOrFail($request->id);
        $cuentas->estado = '0';
        $cuentas->save();
    }

}
