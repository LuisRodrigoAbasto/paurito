<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function __invoke(Request $request)
   {
    // if(!$request->ajax()) return redirect('/');
     $anio=date('Y');
     $compras=DB::table('compras')
     ->select(DB::raw('MONTH(compras.fecha) as mes'),
     DB::raw('YEAR(compras.fecha) as anio'),
     DB::raw('SUM(compras.montoCompra) as total')     )
     ->whereYear('compras.fecha',$anio)
     ->groupBy(DB::raw('MONTH(compras.fecha)'),DB::raw('YEAR(compras.fecha)'))
     ->get();

     $ventas=DB::table('ventas')
        ->select(DB::raw('MONTH(ventas.fecha) as mes'),
        DB::raw('YEAR(ventas.fecha) as anio'),
        DB::raw('SUM(ventas.montoVenta) as total'))
        ->whereYear('ventas.fecha',$anio)
        ->groupBy(DB::raw('MONTH(ventas.fecha)'),DB::raw('YEAR(ventas.fecha)'))
        ->get();

        $ingresos=DB::table('ingreso_egresos')
        ->select(DB::raw('MONTH(ingreso_egresos.fecha) as mes'),
        DB::raw('YEAR(ingreso_egresos.fecha) as anio'),
        DB::raw('SUM(ingreso_egresos.montoIE) as total'))
        ->where('ingreso_egresos.tipo','=','1')
        ->whereYear('ingreso_egresos.fecha',$anio)
        ->groupBy(DB::raw('MONTH(ingreso_egresos.fecha)'),DB::raw('YEAR(ingreso_egresos.fecha)'))
        ->get();
// tipo=0 es egreso
        $egresos=DB::table('ingreso_egresos')
        ->select(DB::raw('MONTH(ingreso_egresos.fecha) as mes'),
        DB::raw('YEAR(ingreso_egresos.fecha) as anio'),
        DB::raw('SUM(ingreso_egresos.montoIE) as total'))
        ->whereYear('ingreso_egresos.fecha',$anio)
        ->where('ingreso_egresos.tipo','=','0')
        ->groupBy(DB::raw('MONTH(ingreso_egresos.fecha)'),DB::raw('YEAR(ingreso_egresos.fecha)'))
        ->get();
        return ['compras'=>$compras,'ventas'=>$ventas,'ingresos'=>$ingresos,'egresos'=>$egresos,'anio'=>$anio];      
 
    }
}