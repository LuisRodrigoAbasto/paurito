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
     DB::raw('SUM(compras.montoCompra) as total'))
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

        $ingresos=DB::table('ingresos')
        ->select(DB::raw('MONTH(ingresos.fecha) as mes'),
        DB::raw('YEAR(ingresos.fecha) as anio'),
        DB::raw('SUM(ingresos.monto) as total'))
        ->whereYear('ingresos.fecha',$anio)
        ->groupBy(DB::raw('MONTH(ingresos.fecha)'),DB::raw('YEAR(ingresos.fecha)'))
        ->get();
// tipo=0 es egreso
        $egresos=DB::table('egresos')
        ->select(DB::raw('MONTH(egresos.fecha) as mes'),
        DB::raw('YEAR(egresos.fecha) as anio'),
        DB::raw('SUM(egresos.monto) as total'))
        ->whereYear('egresos.fecha',$anio)
        ->groupBy(DB::raw('MONTH(egresos.fecha)'),DB::raw('YEAR(egresos.fecha)'))
        ->get();
        $meses= array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        foreach ($compras as $value) {
            $value->mes=$meses[$value->mes-1];
        }
        foreach ($ventas as $value) {
            $value->mes=$meses[$value->mes-1];
        }
        foreach ($ingresos as $value) {
            $value->mes=$meses[$value->mes-1];
        }
        foreach ($egresos as $value) {
            $value->mes=$meses[$value->mes-1];
        }

        return ['compras'=>$compras,'ventas'=>$ventas,'ingresos'=>$ingresos,'egresos'=>$egresos,'anio'=>$anio];      
 
    }
}