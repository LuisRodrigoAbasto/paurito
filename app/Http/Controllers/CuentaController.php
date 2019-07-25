<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cuenta;
// use DB;

class CuentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $cuentas=Cuenta::orderBy('tipo','asc')
            ->orderBy('nivel1','asc')
            ->orderBy('nivel2','asc')
            ->orderBy('nivel3','asc')
            ->orderBy('nivel4','asc')
            ->paginate(20);
        }
        else{
            $cuentas=Cuenta::where($criterio, 'like','%'.$buscar.'%')
            ->orderBy('tipo','asc')
            ->orderBy('nivel1','asc')
            ->orderBy('nivel2','asc')
            ->orderBy('nivel3','asc')
            ->orderBy('nivel4','asc')->paginate(20);
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
    public function selectCuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->select('nombre','id')
        ->orderBy('nombre','asc')
        ->get();
        return ['cuentas'=>$cuentas];
    }

    public function buscarCuenta(Request $request){
        // if(!$request->ajax()) return redirect('/');
        $tipo = $request->tipo;
        $nivel1 = $request->nivel1;
        $nivel2 = $request->nivel2;
        $nivel3 = $request->nivel3;
        $nivel4 = $request->nivel4;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('tipo','=',$tipo)
        ->where('nivel1','=',$nivel1)
        ->where('nivel2','=',$nivel2)
        ->where('nivel3','=',$nivel3)
        ->where('nivel4','=',$nivel4)
        ->select('id','nombre','telefono','empresa','direccion','tipo','nivel1','nivel2','nivel3','nivel4',
        DB::raw("(select MAX(tipo) from cuentas where estado=1)as tipoU"),
        DB::raw("(select MAX(nivel1) from cuentas where tipo=$tipo and estado=1)as nivel1U"),
        DB::raw("(select MAX(nivel2) from cuentas where tipo=$tipo and nivel1=$nivel1 and estado=1)as nivel2U"),
        DB::raw("(select MAX(nivel3) from cuentas where tipo=$tipo and nivel1=$nivel1 and nivel2=$nivel2 and estado=1)as nivel3U"),
        DB::raw("(select MAX(nivel4) from cuentas where tipo=$tipo and nivel1=$nivel1 and nivel2=$nivel2 and nivel3=$nivel3 and estado=1)as nivel4U"))
        ->groupBy('id','nombre','telefono','empresa','direccion','tipo','nivel1','nivel2','nivel3','nivel4')
        ->take(1)
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
        $cuentas->tipo = $request->tipo;
        $cuentas->nivel1 = $request->nivel1;
        $cuentas->nivel2 = $request->nivel2;
        $cuentas->nivel3 = $request->nivel3;
        $cuentas->nivel4 = $request->nivel4;
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
        $cuentas->tipo = $request->tipo;
        $cuentas->nivel1 = $request->nivel1;
        $cuentas->nivel2 = $request->nivel2;
        $cuentas->nivel3 = $request->nivel3;
        $cuentas->nivel4 = $request->nivel4;
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
