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
        // ->join('compras','cuentas.id','=','compras.idProveedor')
        // ->join('ventas','cuentas.id','=','ventas.idCliente')
        // ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
        // ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
        ->where('c1.nivel','=','1')
        ->where('c1.tipo','<=','3')
        ->select('c1.id','c1.nombre','c1.nivel','c1.tipo','c1.estado')
        ->groupBy('c1.id','c1.nombre','c1.nivel','c1.tipo','c1.estado')
        ->orderBy('c1.tipo','asc')
        ->get();

        for($a=0; $a<count($cuentas);$a++) {
            $cuentas[$a]->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
            ->join('ventas','cuentas.id','=','ventas.idCliente')
            ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
            ->where('ventas.estado','=','1')
            ->where('c1.id','=',$cuentas[$a]->id)
            ->sum('ventas.montoVenta');
            
            $cuentas[$a]->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
            ->join('compras','cuentas.id','=','compras.idProveedor')
            ->where('compras.estado','=','1')
            ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
            ->where('c1.id','=',$cuentas[$a]->id)
            ->sum('compras.montoCompra');
            $cuentas[$a]->montoTotal=$cuentas[$a]->montoVenta + $cuentas[$a]->montoCompra;

            if($cuentas[$a]->montoTotal>0)
            {
                $cuentas[$a]->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                // ->join('compras','cuentas.id','=','compras.idProveedor')
                // ->join('ventas','cuentas.id','=','ventas.idCliente')
    
                // ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                // ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                ->where('c1.id','=',$cuentas[$a]->id)
                ->select('c2.id','c2.nombre','c2.nivel','c2.tipo','c2.nivel1','c2.estado')
                ->groupBy('c2.id','c2.nombre','c2.nivel','c2.tipo','c2.nivel1','c2.estado')
                ->orderBy('c2.tipo','asc')
                ->orderBy('c2.nivel1','asc')
                ->get();

                for($b=0;$b<count($cuentas[$a]->datos) ;$b++) {
                $cuentas[$a]->datos[$b]->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('ventas','cuentas.id','=','ventas.idCliente')
                ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                ->where('ventas.estado','=','1')
                ->where('c1.id','=',$cuentas[$a]->id)
                ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                ->sum('ventas.montoVenta');
                    
                $cuentas[$a]->datos[$b]->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('compras','cuentas.id','=','compras.idProveedor')
                ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                ->where('compras.estado','=','1')
                ->where('c1.id','=',$cuentas[$a]->id)
                ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                ->sum('compras.montoCompra');
                $cuentas[$a]->datos[$b]->montoTotal=$cuentas[$a]->datos[$b]->montoVenta + $cuentas[$a]->datos[$b]->montoCompra;
              
                if($cuentas[$a]->datos[$b]->montoTotal>0)
                {
                $cuentas[$a]->datos[$b]->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                // ->join('compras','cuentas.id','=','compras.idProveedor')
                // ->join('ventas','cuentas.id','=','ventas.idCliente')
                // ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                // ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                ->where('c1.id','=',$cuentas[$a]->id)
                ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                ->select('c3.id','c3.nombre','c3.nivel','c3.tipo','c3.nivel1','c3.nivel2','c3.estado')
                ->groupBy('c3.id','c3.nombre','c3.nivel','c3.tipo','c3.nivel1','c3.nivel2','c3.estado')
                ->orderBy('c3.tipo','asc')
                ->orderBy('c3.nivel1','asc')
                ->orderBy('c3.nivel2','asc')
                ->get();
 
                for($c=0 ; $c<count($cuentas[$a]->datos[$b]->datos) ;$c++) {
    
                    $cuentas[$a]->datos[$b]->datos[$c]->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('ventas','cuentas.id','=','ventas.idCliente')
                    ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                    ->where('ventas.estado','=','1')
                    ->where('c1.id','=',$cuentas[$a]->id)
                    ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                    ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                    ->sum('ventas.montoVenta');
                        
                    $cuentas[$a]->datos[$b]->datos[$c]->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('compras','cuentas.id','=','compras.idProveedor')
                    ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                    ->where('compras.estado','=','1')
                    ->where('c1.id','=',$cuentas[$a]->id)
                    ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                    ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                    ->sum('compras.montoCompra');
                    
                    $cuentas[$a]->datos[$b]->datos[$c]->montoTotal=
                    $cuentas[$a]->datos[$b]->datos[$c]->montoVenta + 
                    $cuentas[$a]->datos[$b]->datos[$c]->montoCompra;
                     
                    if($cuentas[$a]->datos[$b]->datos[$c]->montoTotal>0)
                    {
                    $cuentas[$a]->datos[$b]->datos[$c]->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    // ->join('compras','cuentas.id','=','compras.idProveedor')
                    // ->join('ventas','cuentas.id','=','ventas.idCliente')
                    // ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                    // ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                    ->where('c1.id','=',$cuentas[$a]->id)
                    ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                    ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                    ->select('c4.id','c4.nombre','c4.nivel','c4.tipo','c4.nivel1','c4.nivel2','c4.nivel3','c4.estado')
                    ->groupBy('c4.id','c4.nombre','c4.nivel','c4.tipo','c4.nivel1','c4.nivel2','c4.nivel3','c4.estado')
                    ->orderBy('c4.tipo','asc')
                    ->orderBy('c4.nivel1','asc')
                    ->orderBy('c4.nivel2','asc')
                    ->orderBy('c4.nivel3','asc')
                    ->get();
              
                    for($d=0;$d<count($cuentas[$a]->datos[$b]->datos[$c]->datos) ;$d++) {
                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('ventas','cuentas.id','=','ventas.idCliente')
                        ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                        ->where('ventas.estado','=','1')
                        ->where('c1.id','=',$cuentas[$a]->id)
                        ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                        ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                        ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                        ->sum('ventas.montoVenta');
                            
                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('compras','cuentas.id','=','compras.idProveedor')
                        ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                        ->where('compras.estado','=','1')
                        ->where('c1.id','=',$cuentas[$a]->id)
                        ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                        ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                        ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                        ->sum('compras.montoCompra');
                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->montoTotal=
                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->montoVenta +
                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->montoCompra;
                        if($cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->montoTotal>0)
                        {

                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        // ->join('compras','cuentas.id','=','compras.idProveedor')
                        // ->join('ventas','cuentas.id','=','ventas.idCliente')
                        // ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                        // ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                        ->where('c1.id','=',$cuentas[$a]->id)
                        ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                        ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                        ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                        ->select('cuentas.id','cuentas.nombre','cuentas.nivel','cuentas.tipo','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3','cuentas.nivel4','cuentas.estado')
                        ->groupBy('cuentas.id','cuentas.nombre','cuentas.nivel','cuentas.tipo','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3','cuentas.nivel4','cuentas.estado')
                        ->orderBy('cuentas.tipo','asc')
                        ->orderBy('cuentas.nivel1','asc')
                        ->orderBy('cuentas.nivel2','asc')
                        ->orderBy('cuentas.nivel3','asc')
                        ->orderBy('cuentas.nivel4','asc')
                        ->get();
                  
                        for($f=0;$f<count($cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos) ;$f++) {
                            $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->montoVenta=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                            ->join('ventas','cuentas.id','=','ventas.idCliente')
                            ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                            ->where('ventas.estado','=','1')
                            ->where('c1.id','=',$cuentas[$a]->id)
                            ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                            ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                            ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                            ->where('cuentas.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->id)
                            ->sum('ventas.montoVenta');
                                
                            $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->montoCompra=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                            ->join('compras','cuentas.id','=','compras.idProveedor')
                            ->whereBetween('compras.fecha',[$fechaInicio,$fechaFin])
                            ->where('compras.estado','=','1')
                            ->where('c1.id','=',$cuentas[$a]->id)
                            ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                            ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                            ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                            ->where('cuentas.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->id)
                            ->sum('compras.montoCompra');
                            $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->montoTotal=
                            $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->montoVenta + 
                            $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->montoCompra;
                            if($cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->montoTotal>0)
                            {
                                $ventas=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                                ->join('ventas','cuentas.id','=','ventas.idCliente')
                                ->where('ventas.estado','=','1')
                                ->whereBetween('ventas.fecha',[$fechaInicio,$fechaFin])
                                ->where('c1.id','=',$cuentas[$a]->id)
                                ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                                ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                                ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                                ->where('cuentas.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->id)
                                ->select('ventas.id','ventas.factura','ventas.registro','ventas.fecha','ventas.descripcion',
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
                                ->where('c1.id','=',$cuentas[$a]->id)
                                ->where('c2.id','=',$cuentas[$a]->datos[$b]->id)
                                ->where('c3.id','=',$cuentas[$a]->datos[$b]->datos[$c]->id)
                                ->where('c4.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id)
                                ->where('cuentas.id','=',$cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->id)
                                ->select('compras.id','compras.factura','compras.registro','compras.fecha','compras.descripcion',
                                'compras.montoCompra as montoTotal','compras.tipo')
                                ->orderBy('compras.id','desc')
                                ->get();
                                if(count($ventas)>0 && count($compras)>0){
                                    $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->datos=[$ventas[0],$compras[0]];
                                }
                                else{
                                    if(count($ventas)>0 && count($compras)==0)
                                    {
                                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->datos=$ventas;
                                    }
                                    else{
                                        if(count($ventas)==0 && count($compras)>0)
                                    {
                                        $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->datos=$compras;
                                    }
                                    }
                                }
                                // ksort($cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]->datos);
                               
                            }
                            else{
                                unset($cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->datos[$f]);
                                // $f--;
                            }
                        }
                        }
                        else{
                            unset($cuentas[$a]->datos[$b]->datos[$c]->datos[$d]);
                            // $d--;
                            // $cuentas[$a]->datos[$b]->datos[$c]->datos[$d]->id=100;
                        }
                    }
                  
                }
                else{
                    unset($cuentas[$a]->datos[$b]->datos[$c]);
                    // $c--;
                }
                
                }
              
            }
            
            else{
                unset($cuentas[$a]->datos[$b]);
                // $b--;
            }
            

            }
        }
        else{
            unset($cuentas[$a]);
            // $a--;
        }
     }
        return ['cuentas'=>$cuentas];
    }
    public function lista(Request $request)
    {
        $cuentas=Cuenta::with('cuenta')->get();
        return ['cuentas'=>$cuentas];
    }

}
