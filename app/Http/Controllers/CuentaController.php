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
    public function balanceGeneral(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        // $cuentas=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
        // ->join('cuentas as c3','c4.idCuenta','=','c3.id')
        // ->join('cuentas as c2','c3.idCuenta','=','c2.id')
        // ->join('cuentas as c1','c2.idCuenta','=','c1.id')
        // ->select('cuentas.id','c1.nombre as cuenta1','c2.nombre as cuenta2','c3.nombre as cuenta3','c4.nombre as cuenta4','cuentas.nombre as cuenta5','cuentas.nombre as datos','cuentas.nivel','cuentas.tipo','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3',
        // 'cuentas.nivel4','cuentas.estado')
        // ->orderBy('cuentas.tipo','asc')
        // ->orderBy('cuentas.nivel1','asc')
        // ->orderBy('cuentas.nivel2','asc')
        // ->orderBy('cuentas.nivel3','asc')
        // ->orderBy('cuentas.nivel4','asc')
        // ->get();
        $fechaInicio=$request->fechaInicio;
        $fechaFin=$request->fechaFin;
        $cuentas=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
        ->join('compras','cuentas.id','=','compras.idProveedor')
        ->join('ventas','cuentas.id','=','ventas.idCliente')
        ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
        ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
        ->where('c1.nivel','=','1')
        ->select('c1.id','c1.nombre','c1.nivel','c1.tipo','c1.estado')
        ->groupBy('c1.id','c1.nombre','c1.nivel','c1.tipo','c1.estado')
        ->orderBy('c1.tipo','asc')
        ->get();
        $c1=0;
        foreach ($cuentas as $nivel1) {
            $nivel1->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
            ->join('ventas','cuentas.id','=','ventas.idCliente')
            ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
            ->where('ventas.estado','=','1')
            ->where('c1.id','=',$nivel1->id)
            ->sum('ventas.montoVenta');
            
            $nivel1->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
            ->join('compras','cuentas.id','=','compras.idProveedor')
            ->where('compras.estado','=','1')
            ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
            ->where('c1.id','=',$nivel1->id)
            ->sum('compras.montoCompra');
            $nivel1->montoTotal=$nivel1->montoVenta + $nivel1->montoCompra;

            if($nivel1->montoTotal>0)
            {
                $nivel1->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('compras','cuentas.id','=','compras.idProveedor')
                ->join('ventas','cuentas.id','=','ventas.idCliente')
    
                ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                ->where('c1.id','=',$nivel1->id)
                ->select('c2.id','c2.nombre','c2.nivel','c2.tipo','c2.nivel1','c2.estado')
                ->groupBy('c2.id','c2.nombre','c2.nivel','c2.tipo','c2.nivel1','c2.estado')
                ->orderBy('c2.tipo','asc')
                ->orderBy('c2.nivel1','asc')
                ->get();
                $c2=0;
                foreach ($nivel1->datos as $nivel2) {
                $nivel2->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('ventas','cuentas.id','=','ventas.idCliente')
                ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                ->where('ventas.estado','=','1')
                ->where('c1.id','=',$nivel1->id)
                ->where('c2.id','=',$nivel2->id)
                ->sum('ventas.montoVenta');
                    
                $nivel2->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('compras','cuentas.id','=','compras.idProveedor')
                ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                ->where('compras.estado','=','1')
                ->where('c1.id','=',$nivel1->id)
                ->where('c2.id','=',$nivel2->id)
                ->sum('compras.montoCompra');
                $nivel2->montoTotal=$nivel2->montoVenta + $nivel2->montoCompra;
                if($nivel2->montoTotal>0)
                {
                $nivel2->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('compras','cuentas.id','=','compras.idProveedor')
                ->join('ventas','cuentas.id','=','ventas.idCliente')
                ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                ->where('c1.id','=',$nivel1->id)
                ->where('c2.id','=',$nivel2->id)
                ->select('c3.id','c3.nombre','c3.nivel','c3.tipo','c3.nivel1','c3.nivel2','c3.estado')
                ->groupBy('c3.id','c3.nombre','c3.nivel','c3.tipo','c3.nivel1','c3.nivel2','c3.estado')
                ->orderBy('c3.tipo','asc')
                ->orderBy('c3.nivel1','asc')
                ->orderBy('c3.nivel2','asc')
                ->get();
                $c3=0;
                foreach ($nivel2->datos as $nivel3) {
    
                    $nivel3->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('ventas','cuentas.id','=','ventas.idCliente')
                    ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                    ->where('ventas.estado','=','1')
                    ->where('c1.id','=',$nivel1->id)
                    ->where('c2.id','=',$nivel2->id)
                    ->where('c3.id','=',$nivel3->id)
                    ->sum('ventas.montoVenta');
                        
                    $nivel3->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('compras','cuentas.id','=','compras.idProveedor')
                    ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                    ->where('compras.estado','=','1')
                    ->where('c1.id','=',$nivel1->id)
                    ->where('c2.id','=',$nivel2->id)
                    ->where('c3.id','=',$nivel3->id)
                    ->sum('compras.montoCompra');
                    $nivel3->montoTotal=$nivel3->montoVenta + $nivel3->montoCompra;
                    if($nivel3->montoTotal>0)
                    {
                    $nivel3->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('compras','cuentas.id','=','compras.idProveedor')
                    ->join('ventas','cuentas.id','=','ventas.idCliente')
                    ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                    ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                    ->where('c1.id','=',$nivel1->id)
                    ->where('c2.id','=',$nivel2->id)
                    ->where('c3.id','=',$nivel3->id)
                    ->select('c4.id','c4.nombre','c4.nivel','c4.tipo','c4.nivel1','c4.nivel2','c4.nivel3','c4.estado')
                    ->groupBy('c4.id','c4.nombre','c4.nivel','c4.tipo','c4.nivel1','c4.nivel2','c4.nivel3','c4.estado')
                    ->orderBy('c4.tipo','asc')
                    ->orderBy('c4.nivel1','asc')
                    ->orderBy('c4.nivel2','asc')
                    ->orderBy('c4.nivel3','asc')
                    ->get();
                    $c4=0;
                    foreach ($nivel3->datos as $nivel4) {
                        $nivel4->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('ventas','cuentas.id','=','ventas.idCliente')
                        ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                        ->where('ventas.estado','=','1')
                        ->where('c1.id','=',$nivel1->id)
                        ->where('c2.id','=',$nivel2->id)
                        ->where('c3.id','=',$nivel3->id)
                        ->where('c4.id','=',$nivel4->id)
                        ->sum('ventas.montoVenta');
                            
                        $nivel4->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('compras','cuentas.id','=','compras.idProveedor')
                        ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                        ->where('compras.estado','=','1')
                        ->where('c1.id','=',$nivel1->id)
                        ->where('c2.id','=',$nivel2->id)
                        ->where('c3.id','=',$nivel3->id)
                        ->where('c4.id','=',$nivel4->id)
                        ->sum('compras.montoCompra');
                        $nivel4->montoTotal=$nivel4->montoVenta + $nivel4->montoCompra;
                        if($nivel4->montoTotal>0)
                        {

                        $nivel4->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('compras','cuentas.id','=','compras.idProveedor')
                        ->join('ventas','cuentas.id','=','ventas.idCliente')
                        ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                        ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                        ->where('c1.id','=',$nivel1->id)
                        ->where('c2.id','=',$nivel2->id)
                        ->where('c3.id','=',$nivel3->id)
                        ->where('c4.id','=',$nivel4->id)
                        ->select('cuentas.id','cuentas.nombre','cuentas.nivel','cuentas.tipo','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3','cuentas.nivel4','cuentas.estado')
                        ->groupBy('cuentas.id','cuentas.nombre','cuentas.nivel','cuentas.tipo','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3','cuentas.nivel4','cuentas.estado')
                        ->orderBy('cuentas.tipo','asc')
                        ->orderBy('cuentas.nivel1','asc')
                        ->orderBy('cuentas.nivel2','asc')
                        ->orderBy('cuentas.nivel3','asc')
                        ->orderBy('cuentas.nivel4','asc')
                        ->get();
                        $c5=0;
                        foreach ($nivel4->datos as $nivel5) {
                            $nivel5->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                            ->join('ventas','cuentas.id','=','ventas.idCliente')
                            ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                            ->where('ventas.estado','=','1')
                            ->where('c1.id','=',$nivel1->id)
                            ->where('c2.id','=',$nivel2->id)
                            ->where('c3.id','=',$nivel3->id)
                            ->where('c4.id','=',$nivel4->id)
                            ->where('cuentas.id','=',$nivel5->id)
                            ->sum('ventas.montoVenta');
                                
                            $nivel5->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                            ->join('compras','cuentas.id','=','compras.idProveedor')
                            ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                            ->where('compras.estado','=','1')
                            ->where('c1.id','=',$nivel1->id)
                            ->where('c2.id','=',$nivel2->id)
                            ->where('c3.id','=',$nivel3->id)
                            ->where('c4.id','=',$nivel4->id)
                            ->where('cuentas.id','=',$nivel5->id)
                            ->sum('compras.montoCompra');
                            $nivel5->montoTotal=$nivel5->montoVenta + $nivel5->montoCompra;
                            if($nivel5->montoTotal>0)
                            {
                                $ventas=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                                ->join('ventas','cuentas.id','=','ventas.idCliente')
                                ->where('ventas.estado','=','1')
                                ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                                ->where('c1.id','=',$nivel1->id)
                                ->where('c2.id','=',$nivel2->id)
                                ->where('c3.id','=',$nivel3->id)
                                ->where('c4.id','=',$nivel4->id)
                                ->select('ventas.id','ventas.factura','ventas.registro','ventas.fecha',
                                'ventas.montoVenta as montoTotal','ventas.tipo')
                                ->orderBy('ventas.id','desc')
                                ->get();

                                $compras=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                                ->join('compras','cuentas.id','=','compras.idProveedor')
                                ->where('compras.estado','=','1')
                                ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                                ->where('c1.id','=',$nivel1->id)
                                ->where('c2.id','=',$nivel2->id)
                                ->where('c3.id','=',$nivel3->id)
                                ->where('c4.id','=',$nivel4->id)
                                ->select('compras.id','compras.factura','compras.registro','compras.fecha',
                                'compras.montoCompra as montoTotal','compras.tipo')
                                ->orderBy('compras.id','desc')
                                ->get();
                                if(count($ventas)>0 && count($compras)>0){
                                    $nivel5->datos=[$ventas[0],$compras[0]];
                                }
                                else{
                                    if(count($ventas)>0 && count($compras)==0)
                                    {
                                        $nivel5->datos=$ventas;
                                    }
                                    else{
                                        if(count($ventas)==0 && count($compras)>0)
                                        $nivel5->datos=$compras;
                                    }
                                }
                                // ksort($cuentas[$c1]->datos[$c2]->datos[$c3]->datos[$c4]->datos[$c5]->datos);
                                $c5++;
                            }
                            else{
                                unset($cuentas[$c1]->datos[$c2]->datos[$c3]->datos[$c4]->datos[$c5]);
                            }
                        }

                        $c4++;
                        }
                        else{
                            unset($cuentas[$c1]->datos[$c2]->datos[$c3]->datos[$c4]);
                        }
                    }
                    $c3++;
                }
                else{
                    unset($cuentas[$c1]->datos[$c2]->datos[$c3]);
                }
                }
                $c2++;
            }
            else{
                unset($cuentas[$c1]->datos[$c2]);
            }

            }
            $c1++;
        }
        else{
            unset($cuentas[$c1]);
        }
     }
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

        if($cuentas->nivel==1)
        {
            $cuentas->idCuenta=null;
        }
        else
        {
            $cuentas->idCuenta=$request->idCuenta;
        }
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
        $cuentas->nivel=$request->nivel;
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
