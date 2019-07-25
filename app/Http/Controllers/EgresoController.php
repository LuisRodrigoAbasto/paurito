<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\IngresoEgreso;
use App\DetalleIE;

use Illuminate\Http\Request;

class EgresoController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');
    
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            if($buscar=='')
            {
                $ingreso_egreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
                ->select('ingresos_egresos.id','cuentas.nombre','fecha','montoIE','ingresos_egresos.descripcion','ingresos_egresos.estado')
                ->orderBy('ingresos_egresos.estado','desc')->orderBy('ingresos_egresos.id','desc')->paginate(5);
            }
            else{
                if($criterio=='descripcion'){
                    $ingreso_egreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
                    ->select('ingresos_egresos.id','cuentas.nombre','fecha','montoIE','ingresos_egresos.descripcion','ingresos_egresos.estado')
                    ->where('ingresos_egresos.'.$criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('ingresos_egresos.estado','desc')
                    ->orderBy('ingresos_egresos.id','desc')->paginate(5);
                }
                else{
                    $ingreso_egreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
                    ->select('ingresos_egresos.id','cuentas.nombre','fecha','montoIE','ingresos_egresos.descripcion','ingresos_egresos.estado')
                    ->where('cuentas.'.$criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('ingresos_egresos.estado','desc')
                    ->orderBy('ingresos_egresos.id','desc')->paginate(5);
                }
                
            }
         
            return [
                'pagination' => [
                    'total'        => $ingreso_egreso->total(),
                    'current_page' => $ingreso_egreso->currentPage(),
                    'per_page'     => $ingreso_egreso->perPage(),
                    'last_page'    => $ingreso_egreso->lastPage(),
                    'from'         => $ingreso_egreso->firstItem(),
                    'to'           => $ingreso_egreso->lastItem(),
                ],
                'ingresos_egresos' => $ingreso_egreso
            ];
            
        }
        public function obtenerVenta(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
    
            $id = $request->id;
          
                $venta= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
                ->select('ingresos_egresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
                'fecha','pago','cantidad','descripcion','montoVenta','ingresos_egresos.estado')
                ->where('ingresos_egresos.id','=',$id)
                ->orderBy('ingresos_egresos.id','desc')
                ->take(1)
                ->get();
          
            return ['venta' => $venta];
            
        }
        public function obtenerDetalles(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $detalles = DetalleVenta::join('ingresos_egresos','ingresos_egresos.id','=','detalle_ingresos_egresos.idVenta')
            ->join('productos','productos.id','detalle_ingresos_egresos.idProducto')
            ->select('productos.id as idProducto','productos.nombre as producto','detalle_ingresos_egresos.cantidad','productos.unidad','detalle_ingresos_egresos.precio','detalle_ingresos_egresos.descripcionD')
            ->where('detalle_ingresos_egresos.idVenta','=',$id)
            ->orderBy('detalle_ingresos_egresos.idProducto','desc')
            ->get();
            return ['detalles'=>$detalles];
        }
        public function pdf(Request $request, $id)
        {
            $venta= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
            ->select('ingresos_egresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
            'fecha','pago','cantidad','descripcion','montoVenta','ingresos_egresos.estado')
            ->where('ingresos_egresos.id','=',$id)
            ->orderBy('ingresos_egresos.id','desc')
            ->take(1)
            ->get();
    
            $detalles = DetalleVenta::join('ingresos_egresos','ingresos_egresos.id','=','detalle_ingresos_egresos.idVenta')
            ->join('productos','productos.id','detalle_ingresos_egresos.idProducto')
            ->select('productos.id as idProducto','productos.nombre as producto','detalle_ingresos_egresos.cantidad','productos.unidad','detalle_ingresos_egresos.precio','detalle_ingresos_egresos.descripcionD')
            ->where('detalle_ingresos_egresos.idVenta','=',$id)
            ->orderBy('detalle_ingresos_egresos.idProducto','desc')
            ->get();
    
            $numventa= IngresoEgreso::select('ingresos_egresos.id')->where('id',$id)->get();
            
            $pdf = \PDF::loadView('pdf.venta',['venta'=>$venta,'detalles'=>$detalles]);
            return $pdf->download('venta_'.$numventa[0]->id.'.pdf');
    
        }
        public function listaringresos_egresos(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $detalles = DetalleVenta::join('ingresos_egresos','ingresos_egresos.id','=','detalle_ingresos_egresos.idVenta')
            ->join('productos','productos.id','detalle_ingresos_egresos.idProducto')
            ->where('detalle_ingresos_egresos.idVenta','=',$id) 
            ->select('productos.id as idProducto','productos.nombre as producto','detalle_ingresos_egresos.cantidad','productos.codigo','productos.unidad','detalle_ingresos_egresos.precio','detalle_ingresos_egresos.descripcionD')
            ->get();
            return ['detalles'=>$detalles];
        }
        
        public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            try{
                DB::beginTransaction();
                $mytime= Carbon::now('America/La_Paz');
                $venta = new IngresoEgreso();
                $venta->idCuenta = $request->idCuenta;
                $venta->idFormula =$request->idFormula;
                $venta->fecha = $mytime->toDateTimeString();
                $venta->pago = $request->pago;
                $venta->cantidad = $request->cantidad;
                $venta->montoVenta = $request->montoVenta;
                $venta->descripcion = $request->descripcion;
                $venta->estado = '1';
                $venta->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleVenta();
                    $detalle->idVenta= $venta->id;
                    $detalle->idProducto= $det['idProducto'];
                    $detalle->cantidad = $det['cantidad'];   
                    $detalle->precio = $det['precio']; 
                    if($venta->idFormula){
                        $detalle->descripcionD = $det['descripcionD'];
                    }
                    else
                    {
                        $detalle->descripcionD = '';
                    }
                    $detalle->save();
                } 
                DB::commit();
            } catch (Exception $e){
                DB::rollBack();
            }
        }
    
        public function update(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            try{
            $mytime= Carbon::now('America/La_Paz');
            $venta = IngresoEgreso::findOrFail($request->id);
            $venta->idCuenta = $request->idCuenta;
            $venta->idFormula = $request->idFormula;
            $venta->fecha = $mytime->toDateTimeString();
            $venta->pago = $request->pago;
            $venta->cantidad = $request->cantidad;
            $venta->descripcion = $request->descripcion;
            $venta->montoVenta = $request->montoVenta;
            $venta->estado = '1';
            $venta->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
        }
    
        public function insertarDetalle(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            try{            
                $venta->idFormula = $request->idFormula;
                $idVenta = $request->idVenta;
                $detalles = $request->data;
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleVenta();
                    $detalle->idVenta= $idVenta;
                    $detalle->idProducto= $det['idProducto'];
                    $detalle->cantidad = $det['cantidad'];   
                    $detalle->precio = $det['precio'];
                    if($venta->idFormula){
                        $detalle->descripcionD = $det['descripcionD'];
                    }
                    else
                    {
                        $detalle->descripcionD = '';
                    }
                    $detalle->save();
                }          
            
            DB::commit();
            } catch (Exception $e){
                DB::rollBack();
            }
        }
    
        public function eliminarDetalle(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            try{
            $detalle = DetalleVenta::find($request->idVenta);
            if($request->idVenta){
            $detalle ->delete();        
            DB::commit();
            }
            else{
                 DB::rollBack();
            }
            } catch (Exception $e){
                DB::rollBack();
            }
        }
        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $ingreso_egreso = IngresoEgreso::findOrFail($request->id);
            $ingreso_egreso->estado = '0';
            $ingreso_egreso->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $ingreso_egreso = IngresoEgreso::findOrFail($request->id);
            $ingreso_egreso->estado = '1';
            $ingreso_egreso->save();
        }
           
    }