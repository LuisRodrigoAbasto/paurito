<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cuenta;
use App\Venta;
use App\DetalleVenta;
use App\Producto;

class EstadoResultadoController extends ApiController
{
    public function index(Request $request)
    {   
        if(!$request->ajax()) return redirect('/');
        $fechaInicio=$request->fechaInicio;
        $fechaFin=$request->fechaFin;
        $cuentas=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
        ->where('c1.nivel','=','1')
        ->where('c1.nivel1','>','3')
        ->select('c1.id','c1.nombre','c1.nivel','c1.nivel1','c1.estado')
        ->groupBy('c1.id','c1.nombre','c1.nivel','c1.nivel1','c1.estado')
        ->orderBy('c1.nivel1','asc')
        ->get();

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

            $nivel1->montoIngreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
            ->join('detalle_ingresos','cuentas.id','=','detalle_ingresos.idCuenta')
            ->join('ingresos','detalle_ingresos.idIngreso','=','ingresos.id')
            ->whereBetween('ingresos.fecha',[$fechaInicio,$fechaFin])
            ->where('ingresos.estado','=','1')
            ->where('detalle_ingresos.estado','=','1')
            ->where('c1.id','=',$nivel1->id)
            ->sum('detalle_ingresos.debe');
            
            $nivel1->montoEgreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
            ->join('detalle_egresos','cuentas.id','=','detalle_egresos.idCuenta')
            ->join('egresos','detalle_egresos.idEgreso','=','egresos.id')
            ->whereBetween('egresos.fecha',[$fechaInicio,$fechaFin])
            ->where('egresos.estado','=','1')
            ->where('detalle_egresos.estado','=','1')
            ->where('c1.id','=',$nivel1->id)
            ->sum('detalle_egresos.debe');

            $nivel1->montoTotal=$nivel1->montoVenta + $nivel1->montoCompra +
            $nivel1->montoIngreso + $nivel1->montoEgreso;

            if($nivel1->montoTotal>0)
            {
                $nivel1->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->where('c1.id','=',$nivel1->id)
                ->select('c2.id','c2.nombre','c2.nivel','c2.nivel1','c2.nivel2','c2.estado')
                ->groupBy('c2.id','c2.nombre','c2.nivel','c2.nivel1','c2.nivel2','c2.estado')
                ->orderBy('c2.nivel1','asc')
                ->orderBy('c2.nivel2','asc')
                ->get();

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

                $nivel2->montoIngreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('detalle_ingresos','cuentas.id','=','detalle_ingresos.idCuenta')
                ->join('ingresos','detalle_ingresos.idIngreso','=','ingresos.id')
                ->whereBetween('ingresos.fecha',[$fechaInicio,$fechaFin])
                ->where('ingresos.estado','=','1')
                ->where('detalle_ingresos.estado','=','1')
                ->where('c1.id','=',$nivel1->id)
                ->where('c2.id','=',$nivel2->id)
                ->sum('detalle_ingresos.debe');
                
                $nivel2->montoEgreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->join('detalle_egresos','cuentas.id','=','detalle_egresos.idCuenta')
                ->join('egresos','detalle_egresos.idEgreso','=','egresos.id')
                ->whereBetween('egresos.fecha',[$fechaInicio,$fechaFin])
                ->where('egresos.estado','=','1')
                ->where('detalle_egresos.estado','=','1')
                ->where('c1.id','=',$nivel1->id)
                ->where('c2.id','=',$nivel2->id)
                ->sum('detalle_egresos.debe');

                $nivel2->montoTotal=$nivel2->montoVenta + $nivel2->montoCompra +
                $nivel2->montoIngreso + $nivel2->montoEgreso;
                
                if($nivel2->montoTotal>0)
                {
                $nivel2->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                ->where('c1.id','=',$nivel1->id)
                ->where('c2.id','=',$nivel2->id)
                ->select('c3.id','c3.nombre','c3.nivel','c3.nivel1','c3.nivel2','c3.nivel3','c3.estado')
                ->groupBy('c3.id','c3.nombre','c3.nivel','c3.nivel1','c3.nivel2','c3.nivel3','c3.estado')
                ->orderBy('c3.nivel1','asc')
                ->orderBy('c3.nivel2','asc')
                ->orderBy('c3.nivel3','asc')
                ->get();

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

                    $nivel3->montoIngreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('detalle_ingresos','cuentas.id','=','detalle_ingresos.idCuenta')
                    ->join('ingresos','detalle_ingresos.idIngreso','=','ingresos.id')
                    ->whereBetween('ingresos.fecha',[$fechaInicio,$fechaFin])
                    ->where('ingresos.estado','=','1')
                    ->where('detalle_ingresos.estado','=','1')
                    ->where('c1.id','=',$nivel1->id)
                    ->where('c2.id','=',$nivel2->id)
                    ->where('c3.id','=',$nivel3->id)
                    ->sum('detalle_ingresos.debe');
                    
                    $nivel3->montoEgreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->join('detalle_egresos','cuentas.id','=','detalle_egresos.idCuenta')
                    ->join('egresos','detalle_egresos.idEgreso','=','egresos.id')
                    ->whereBetween('egresos.fecha',[$fechaInicio,$fechaFin])
                    ->where('egresos.estado','=','1')
                    ->where('detalle_egresos.estado','=','1')
                    ->where('c1.id','=',$nivel1->id)
                    ->where('c2.id','=',$nivel2->id)
                    ->where('c3.id','=',$nivel3->id)
                    ->sum('detalle_egresos.debe');

                    $nivel3->montoTotal=$nivel3->montoVenta + $nivel3->montoCompra +
                    $nivel3->montoIngreso + $nivel3->montoEgreso;

                    if($nivel3->montoTotal>0)
                    {
                    $nivel3->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                    ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                    ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                    ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                    ->where('c1.id','=',$nivel1->id)
                    ->where('c2.id','=',$nivel2->id)
                    ->where('c3.id','=',$nivel3->id)
                    ->select('c4.id','c4.nombre','c4.nivel','c4.nivel1','c4.nivel2','c4.nivel3','c4.nivel4','c4.estado')
                    ->groupBy('c4.id','c4.nombre','c4.nivel','c4.nivel1','c4.nivel2','c4.nivel3','c4.nivel4','c4.estado')
                    ->orderBy('c4.nivel1','asc')
                    ->orderBy('c4.nivel2','asc')
                    ->orderBy('c4.nivel3','asc')
                    ->orderBy('c4.nivel4','asc')
                    ->get();

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

                        $nivel4->montoIngreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('detalle_ingresos','cuentas.id','=','detalle_ingresos.idCuenta')
                        ->join('ingresos','detalle_ingresos.idIngreso','=','ingresos.id')
                        ->whereBetween('ingresos.fecha',[$fechaInicio,$fechaFin])
                        ->where('ingresos.estado','=','1')
                        ->where('detalle_ingresos.estado','=','1')
                        ->where('c1.id','=',$nivel1->id)
                        ->where('c2.id','=',$nivel2->id)
                        ->where('c3.id','=',$nivel3->id)
                        ->where('c4.id','=',$nivel4->id)
                        ->sum('detalle_ingresos.debe');
                        
                        $nivel4->montoEgreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->join('detalle_egresos','cuentas.id','=','detalle_egresos.idCuenta')
                        ->join('egresos','detalle_egresos.idEgreso','=','egresos.id')
                        ->whereBetween('egresos.fecha',[$fechaInicio,$fechaFin])
                        ->where('egresos.estado','=','1')
                        ->where('detalle_egresos.estado','=','1')
                        ->where('c1.id','=',$nivel1->id)
                        ->where('c2.id','=',$nivel2->id)
                        ->where('c3.id','=',$nivel3->id)
                        ->where('c4.id','=',$nivel4->id)
                        ->sum('detalle_egresos.debe');

                        $nivel4->montoTotal=$nivel4->montoVenta + $nivel4->montoCompra +
                        $nivel4->montoIngreso + $nivel4->montoEgreso;

                        
                        if($nivel4->montoTotal>0)
                        {
                        $nivel4->datos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                        ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                        ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                        ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                        ->where('c1.id','=',$nivel1->id)
                        ->where('c2.id','=',$nivel2->id)
                        ->where('c3.id','=',$nivel3->id)
                        ->where('c4.id','=',$nivel4->id)
                        ->select('cuentas.id','cuentas.nombre','cuentas.nivel','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3','cuentas.nivel4','cuentas.nivel5','cuentas.estado')
                        ->groupBy('cuentas.id','cuentas.nombre','cuentas.nivel','cuentas.nivel1','cuentas.nivel2','cuentas.nivel3','cuentas.nivel4','cuentas.nivel5','cuentas.estado')
                        ->orderBy('cuentas.nivel1','asc')
                        ->orderBy('cuentas.nivel2','asc')
                        ->orderBy('cuentas.nivel3','asc')
                        ->orderBy('cuentas.nivel4','asc')
                        ->orderBy('cuentas.nivel5','asc')
                        ->get();

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

                            $nivel5->montoIngreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                            ->join('detalle_ingresos','cuentas.id','=','detalle_ingresos.idCuenta')
                            ->join('ingresos','detalle_ingresos.idIngreso','=','ingresos.id')
                            ->whereBetween('ingresos.fecha',[$fechaInicio,$fechaFin])
                            ->where('ingresos.estado','=','1')
                            ->where('detalle_ingresos.estado','=','1')
                            ->where('c1.id','=',$nivel1->id)
                            ->where('c2.id','=',$nivel2->id)
                            ->where('c3.id','=',$nivel3->id)
                            ->where('c4.id','=',$nivel4->id)
                            ->where('cuentas.id','=',$nivel5->id)
                            ->sum('detalle_ingresos.debe');
                            
                            $nivel5->montoEgreso=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                            ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                            ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                            ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                            ->join('detalle_egresos','cuentas.id','=','detalle_egresos.idCuenta')
                            ->join('egresos','detalle_egresos.idEgreso','=','egresos.id')
                            ->whereBetween('egresos.fecha',[$fechaInicio,$fechaFin])
                            ->where('egresos.estado','=','1')
                            ->where('detalle_egresos.estado','=','1')
                            ->where('c1.id','=',$nivel1->id)
                            ->where('c2.id','=',$nivel2->id)
                            ->where('c3.id','=',$nivel3->id)
                            ->where('c4.id','=',$nivel4->id)
                            ->where('cuentas.id','=',$nivel5->id)
                            ->sum('detalle_egresos.debe');

                            $nivel5->montoTotal=$nivel5->montoVenta + $nivel5->montoCompra +
                            $nivel5->montoIngreso + $nivel5->montoEgreso;
                            

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
                                ->where('cuentas.id','=',$nivel5->id)
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
                                ->where('c1.id','=',$nivel1->id)
                                ->where('c2.id','=',$nivel2->id)
                                ->where('c3.id','=',$nivel3->id)
                                ->where('c4.id','=',$nivel4->id)
                                ->where('cuentas.id','=',$nivel5->id)
                                ->select('compras.id','compras.factura','compras.registro','compras.fecha','compras.descripcion',
                                'compras.montoCompra as montoTotal','compras.tipo')
                                ->orderBy('compras.id','desc')
                                ->get();

                                $ingresos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                                ->join('detalle_ingresos','cuentas.id','=','detalle_ingresos.idCuenta')
                                ->join('ingresos','detalle_ingresos.idIngreso','=','ingresos.id')
                                ->whereBetween('ingresos.fecha',[$fechaInicio,$fechaFin])
                                ->where('ingresos.estado','=','1')
                                ->where('c1.id','=',$nivel1->id)
                                ->where('c2.id','=',$nivel2->id)
                                ->where('c3.id','=',$nivel3->id)
                                ->where('c4.id','=',$nivel4->id)
                                ->where('cuentas.id','=',$nivel5->id)
                                ->select('ingresos.id','ingresos.factura','ingresos.registro','ingresos.fecha','ingresos.descripcion',
                                'ingresos.monto as montoTotal','ingresos.tipo')
                                ->groupBy('ingresos.id','ingresos.factura','ingresos.registro','ingresos.fecha','ingresos.descripcion',
                                'ingresos.monto','ingresos.tipo')
                                ->orderBy('ingresos.id','desc')
                                ->get();
                                
                                $egresos=Cuenta::join('cuentas as c4','cuentas.idCuenta','=','c4.id')
                                ->join('cuentas as c3','c4.idCuenta','=','c3.id')
                                ->join('cuentas as c2','c3.idCuenta','=','c2.id')
                                ->join('cuentas as c1','c2.idCuenta','=','c1.id')
                                ->join('detalle_egresos','cuentas.id','=','detalle_egresos.idCuenta')
                                ->join('egresos','detalle_egresos.idEgreso','=','egresos.id')
                                ->whereBetween('egresos.fecha',[$fechaInicio,$fechaFin])
                                ->where('egresos.estado','=','1')
                                ->where('c1.id','=',$nivel1->id)
                                ->where('c2.id','=',$nivel2->id)
                                ->where('c3.id','=',$nivel3->id)
                                ->where('c4.id','=',$nivel4->id)
                                ->where('cuentas.id','=',$nivel5->id)
                                ->select('egresos.id','egresos.factura','egresos.registro','egresos.fecha','egresos.descripcion',
                                'egresos.monto as montoTotal','egresos.tipo')
                                ->groupBy('egresos.id','egresos.factura','egresos.registro','egresos.fecha','egresos.descripcion',
                                'egresos.monto','egresos.tipo')
                                ->orderBy('egresos.id','desc')
                                ->get();

                                $data=array();
                                if(count($ventas)>0)
                                {
                                    foreach ($ventas as $value) {
                                        array_push($data,$value);
                                    }
                                    // $data=$ventas;
                                }
                                if(count($compras)>0)
                                {                                   
                                    // if(count($data)>0)
                                    // {
                                        foreach ($compras as $value1)
                                        { 
                                            array_push($data,$value1);
                                        }
                                        // array_push($data,$compras);
                                    // }
                                    // else{
                                    //     $data=$compras;
                                    // }
                                }
                                if(count($ingresos)>0)
                                {
                                    // if(count($data)>0)
                                    // {
                                        foreach ($ingresos as $value2)
                                        { 
                                            array_push($data,$value2);
                                        }
                                        // $data=array($data,$ingresos);
                                    // }
                                    // else{
                                    //     $data=array($ingresos);
                                    // }
                                }

                                if(count($egresos)>0)
                                {
                                    // if(count($data)>0)
                                    // {
                                        foreach ($egresos as $value3)
                                        { 
                                        array_push($data,$value3);
                                        }
                                    // }
                                    // else{
                                    //     $data=$egresos;
                                    // }
                                }
                                // return $data;
                                
                                usort($data, object_sorter('registro','DESC'));

                                // return $data;
                                $nivel5->datos=$data;

                                // if(count($ventas)>0 && count($compras)>0 && count($ingresos)>0 && count($egresos)>0){
                                //     $nivel5->datos=[$ventas[0],$compras[0],$ingresos[0],$egresos[0]];
                                // }
                                // else{
                                //     if(count($ventas)>0 && count($compras)>0 && count($ingresos)>0 && count($egresos)>0)
                                //     {
                                //         $nivel5->datos=$ventas;
                                //     }
                                //     else{
                                //         if(count($ventas)==0 && count($compras)>0)
                                //     {
                                //         $nivel5->datos=$compras;
                                //     }
                                //     }
                                // }
                                // ksort($cuentas[$c1]->datos[$c2]->datos[$c3]->datos[$c4]->datos[$c5]->datos);
                                // $c5++;
                            }
                            else{
                                // unset($cuentas[$c1]->datos[$c2]->datos[$c3]->datos[$c4]->datos[$c5]);
                            }
                        }

                        // $c4++;
                        }
                        else{
                            // unset($cuentas[$c1]->datos[$c2]->datos[$c3]->datos[$c4]);
                        }
                    }
                    // $c3++;
                }
                else{
                    // unset($cuentas[$c1]->datos[$c2]->datos[$c3]);
                }
                }
                // $c2++;
            }
            else{
                // unset($cuentas[$c1]->datos[$c2]);
            }

            }
            // $c1++;
        }
        else{
            // unset($cuentas[$c1]);
        }
     }
        return ['cuentas'=>$cuentas];
    }
}
function object_sorter($clave,$orden=null) {
    return function ($a, $b) use ($clave,$orden) {
          $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
          return $result;
    };
}
