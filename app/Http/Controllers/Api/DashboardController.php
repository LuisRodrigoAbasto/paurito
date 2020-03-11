<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends ApiController
{
   public function index(Request $request)
   {
    // if(!$request->ajax()) return redirect('/');
     $year=date('Y');
     $data['compras']=DB::table('compras')
     ->select(DB::raw('MONTH(compras.fecha) as mes'),
     DB::raw('YEAR(compras.fecha) as year'),
     DB::raw('SUM(compras.monto_total) as total'))
     ->whereYear('compras.fecha',$year)
     ->groupBy(DB::raw('MONTH(compras.fecha)'),DB::raw('YEAR(compras.fecha)'))
     ->get();

     $data['ventas']=DB::table('ventas')
        ->select(DB::raw('MONTH(ventas.fecha) as mes'),
        DB::raw('YEAR(ventas.fecha) as year'),
        DB::raw('SUM(ventas.monto_total) as total'))
        ->whereYear('ventas.fecha',$year)
        ->groupBy(DB::raw('MONTH(ventas.fecha)'),DB::raw('YEAR(ventas.fecha)'))
        ->get();

        $data['ingresos']=DB::table('ingresos')
        ->select(DB::raw('MONTH(ingresos.fecha) as mes'),
        DB::raw('YEAR(ingresos.fecha) as year'),
        DB::raw('SUM(ingresos.monto_total) as total'))
        ->whereYear('ingresos.fecha',$year)
        ->groupBy(DB::raw('MONTH(ingresos.fecha)'),DB::raw('YEAR(ingresos.fecha)'))
        ->get();
// tipo=0 es egreso
        $data['egresos']=DB::table('egresos')
        ->select(DB::raw('MONTH(egresos.fecha) as mes'),
        DB::raw('YEAR(egresos.fecha) as year'),
        DB::raw('SUM(egresos.monto_total) as total'))
        ->whereYear('egresos.fecha',$year)
        ->groupBy(DB::raw('MONTH(egresos.fecha)'),DB::raw('YEAR(egresos.fecha)'))
        ->get();
        return $this->sendResponse($data, 'Datos Recuperados Correctamente');
 
    }
}