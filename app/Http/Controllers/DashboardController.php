<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function __invoke(Request $request)
   {
    if(!$request->ajax()) return redirect('/');
     $anio=date('Y');
     $compras=DB::table('compras')
     ->select(DB::raw('MONTHNAME(compras.fecha) as mes'),
     DB::raw('YEAR(compras.fecha) as anio'),
     DB::raw('SUM(compras.montoCompra) as total'))
     ->whereYear('compras.fecha',$anio)
     ->groupBy(DB::raw('MONTHNAME(compras.fecha)'),DB::raw('YEAR(compras.fecha)'))
     ->get();

     $ventas=DB::table('ventas')
        ->select(DB::raw('MONTHNAME(ventas.fecha) as mes'),
        DB::raw('YEAR(ventas.fecha) as anio'),
        DB::raw('SUM(ventas.montoVenta) as total'))
        ->whereYear('ventas.fecha',$anio)
        ->groupBy(DB::raw('MONTHNAME(ventas.fecha)'),DB::raw('YEAR(ventas.fecha)'))
        ->get();

        $ingresos=DB::table('ingresos')
        ->select(DB::raw('MONTHNAME(ingresos.fecha) as mes'),
        DB::raw('YEAR(ingresos.fecha) as anio'),
        DB::raw('SUM(ingresos.monto) as total'))
        ->where('ingresos.tipo','=','1')
        ->whereYear('ingresos.fecha',$anio)
        ->groupBy(DB::raw('MONTHNAME(ingresos.fecha)'),DB::raw('YEAR(ingresos.fecha)'))
        ->get();
// tipo=0 es egreso
        $egresos=DB::table('egresos')
        ->select(DB::raw('MONTHNAME(egresos.fecha) as mes'),
        DB::raw('YEAR(egresos.fecha) as anio'),
        DB::raw('SUM(egresos.monto) as total'))
        ->whereYear('egresos.fecha',$anio)
        ->where('egresos.tipo','=','2')
        ->groupBy(DB::raw('MONTHNAME(egresos.fecha)'),DB::raw('YEAR(egresos.fecha)'))
        ->get();
        return ['compras'=>$compras,'ventas'=>$ventas,'ingresos'=>$ingresos,'egresos'=>$egresos,'anio'=>$anio];      
 
    }
}