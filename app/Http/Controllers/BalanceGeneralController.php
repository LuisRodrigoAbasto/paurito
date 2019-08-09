<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cuenta;

class BalanceGeneralController extends Controller
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
    public function buscarCuenta(Request $request){
        // if(!$request->ajax()) return redirect('/');
        $nivel=$request->nivel;
        $tipo = $request->tipo;
        $nivel1 = $request->nivel1;
        $nivel2 = $request->nivel2;
        $nivel3 = $request->nivel3;
        $nivel4 = $request->nivel4;
        if($nivel==1){
        $cuentas = Cuenta::where('estado','=','1')
        ->where('tipo','>','0')
        ->where('nivel1','=',$nivel1)
        ->where('nivel2','=',$nivel2)
        ->where('nivel3','=',$nivel3)
        ->where('nivel4','=',$nivel4)
        ->max('tipo');
        }else{
        if($nivel==2){
            $cuentas = Cuenta::where('estado','=','1')
            ->where('tipo','=',$tipo)
            ->where('nivel1','>','0')
            ->where('nivel2','=',$nivel2)
            ->where('nivel3','=',$nivel3)
            ->where('nivel4','=',$nivel4)
            ->max('nivel1');
            }
            else{
            if($nivel==3){
                $cuentas = Cuenta::where('estado','=','1')
                ->where('tipo','=',$tipo)
                ->where('nivel1','=',$nivel1)
                ->where('nivel2','>','0')
                ->where('nivel3','=',$nivel3)
                ->where('nivel4','=',$nivel4)
                ->max('nivel2');
                }
                else{
                if($nivel==4){
                    $cuentas = Cuenta::where('estado','=','1')
                    ->where('tipo','=',$tipo)
                    ->where('nivel1','=',$nivel1)
                    ->where('nivel2','=',$nivel2)
                    ->where('nivel3','>','0')
                    ->where('nivel4','=',$nivel4)
                    ->max('nivel3');
                    }
                    else{
                    if($nivel==5){
                        $cuentas = Cuenta::where('estado','=','1')
                        ->where('tipo','=',$tipo)
                        ->where('nivel1','=',$nivel1)
                        ->where('nivel2','=',$nivel2)
                        ->where('nivel3','=',$nivel3)
                        ->where('nivel4','>','0')
                        ->max('nivel4');
                        }
                    }
                }
            }
       }

        return ['cuentas'=>$cuentas];
    }
    public function listarPdf(Request $request)
    {
        $cuentas=Cuenta::where('estado','=','1')
        // select('id','tipo','nivel1','nivel2','nivel3','nivel4','nombre','telefono','empresa','direccion','nivel','estado')
        
        ->orderBy('tipo','asc')
        ->orderBy('nivel1','asc')
        ->orderBy('nivel2','asc')
        ->orderBy('nivel3','asc')
        ->orderBy('nivel4','asc')
        ->get();

        $cont=Cuenta::count();
        $pdf = \PDF::loadView('pdf.cuentaspdf',['cuentas'=>$cuentas,'cont'=>$cont]);
        return $pdf->download('cuentas.pdf');
    }
    
    public function cuenta(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('nivel4','=','0')
        ->where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->get();
        return ['cuentas'=>$cuentas];
    }
    public function selectCuenta(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
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
        // if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $cuentas = Cuenta::where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->limit(10)
        ->get();
        return ['cuentas'=>$cuentas];
    }

}
