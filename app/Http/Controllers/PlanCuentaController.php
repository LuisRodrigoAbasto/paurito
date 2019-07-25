<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\PlanCuenta;

class PlanCuentaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $plancuentas=PlanCuenta::join('cuentas','cuentas.id','=','plan_cuentas.idCuenta')
            ->select('plan_cuentas.id','cuentas.tipo as tipo','cuentas.nombre as cuenta','plan_cuentas.fecha',
            'plan_cuentas.nombre','plan_cuentas.descripcion','plan_cuentas.cantidad','plan_cuentas.tipo as desicion','plan_cuentas.estado')
            ->orderBy('plan_cuentas.id','desc')->paginate(5);
        }
        else{
            $plancuentas=PlanCuenta::join('cuentas','cuentas.id','=','plan_cuentas.idCuenta')
            ->select('plan_cuentas.id','cuentas.tipo as tipo','cuentas.nombre as cuenta','plan_cuentas.fecha',
            'plan_cuentas.nombre','plan_cuentas.descripcion','plan_cuentas.cantidad','plan_cuentas.tipo as desicion','plan_cuentas.estado')
            ->where('plan_cuentas'.$criterio, 'like','%'.$buscar.'%')
            ->orderBy('plan_cuentas.id','desc')->paginate(5);
        }
     
        return [
            'pagination' => [
                'total'        => $plancuentas->total(),
                'current_page' => $plancuentas->currentPage(),
                'per_page'     => $plancuentas->perPage(),
                'last_page'    => $plancuentas->lastPage(),
                'from'         => $plancuentas->firstItem(),
                'to'           => $plancuentas->lastItem(),
            ],
            'plancuentas' => $plancuentas
        ];
    }
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $mytime= Carbon::now('America/La_Paz');
        $plancuentas = new PlanCuenta();
        $plancuentas->idCuenta = $request->idCuenta;
        $plancuentas->fecha = $mytime->toDateTimeString();
        $plancuentas->nombre = $request->nombre;
        $plancuentas->descripcion = $request->descripcion;
        $plancuentas->cantidad = $request->cantidad;
        $plancuentas->tipo = $request->tipo;
        $plancuentas->estado = '1';
        $plancuentas->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $mytime= Carbon::now('America/La_Paz');
        $plancuentas = PlanCuenta::findOrFail($request->id);
        $plancuentas->idCuenta = $request->idCuenta;
        $plancuentas->fecha = $mytime->toDateTimeString();
        $plancuentas->nombre = $request->nombre;
        $plancuentas->descripcion = $request->descripcion;
        $plancuentas->cantidad = $request->cantidad;
        $plancuentas->tipo = $request->tipo;
        $plancuentas->estado = '1';
        $plancuentas->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $plancuentas = PlanCuenta::findOrFail($request->id);
        $plancuentas->estado = '1';
        $plancuentas->save();
    }
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $plancuentas = PlanCuenta::findOrFail($request->id);
        $plancuentas->estado = '0';
        $plancuentas->save();
    }

}