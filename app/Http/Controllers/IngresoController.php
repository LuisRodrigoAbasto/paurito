<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\IngresoEgreso;
use App\DetalleIE;

class IngresoController extends Controller
{
    public function index(Request $request){
        // if(!$request->ajax()) return redirect('/');
    
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            if($buscar=='')
            {
                $ingresos= IngresoEgreso::where('tipo','=','1')->orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
            }
            else{
                    $ingresos= IngresoEgreso::where('tipo','=','1')
                    ->where('ingreso_egresos.'.$criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('estado','desc')
                    >orderBy('id','desc')->paginate(5);
            }
         
            return [
                'pagination' => [
                    'total'        => $ingresos->total(),
                    'current_page' => $ingresos->currentPage(),
                    'per_page'     => $ingresos->perPage(),
                    'last_page'    => $ingresos->lastPage(),
                    'from'         => $ingresos->firstItem(),
                    'to'           => $ingresos->lastItem(),
                ],
                'ingresos' => $ingresos
            ];
            
        }
        public function obtenerVenta(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
    
            $id = $request->id;
          
                $ingreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
                ->select('ingresos_egresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
                'fecha','pago','cantidad','descripcion','montoVenta','ingresos_egresos.estado')
                ->where('ingresos_egresos.id','=',$id)
                ->orderBy('ingresos_egresos.id','desc')
                ->take(1)
                ->get();
          
            return ['ingreso' => $ingreso];
            
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
            $ingreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
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
            
            $pdf = \PDF::loadView('pdf.venta',['venta'=>$ingreso,'detalles'=>$detalles]);
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
                $ingreso = new IngresoEgreso();
                $ingreso->idCuenta = $request->idCuenta;
                $ingreso->fecha = $mytime->toDateTimeString();
                $ingreso->montoIE = $request->montoIE;
                $ingreso->descripcion = $request->descripcion;
                $ingreso->tipo = $request->tipo;
                $ingreso->estado = '1';
                $ingreso->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleIE();
                    $detalle->idIE= $ingreso->idIE;
                    $detalle->idCuenta = $det['idCuenta'];
                    $detalle->debe = $det['debe'];   
                    $detalle->haber = $det['haber']; 
                    $detalle->descripcionD = $det['descripcionD'];
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
            DB::beginTransaction();  
            $mytime= Carbon::now('America/La_Paz');
            $ingreso = IngresoEgreso::findOrFail($request->id);
            $ingreso->idCuenta = $request->idCuenta;
            $ingreso->fecha = $mytime->toDateTimeString();
            $ingreso->montoIE = $request->montoIE;
            $ingreso->descripcion = $request->descripcion;
            $ingreso->tipo = $request->tipo;
            $ingreso->estado = '1';
            $ingreso->save();

            $detalle = DetalleIE::where('idIE','=',$ingreso->id)->update(['estado'=>'0']);

            $detalles = $request->data;//Array de detalles
                 //Recorro todos los elementos
                
                 foreach($detalles as $ep=>$det)
                 {         
                    $descripcionDT = $det['descripcionD'];
                    if($descripcionDT==''){
                        $descripcionDT =null;
                    }                
                     $detalleED=DetalleIE::updateOrInsert(['idIE' =>$ingreso->id,'idCuenta'=>$det['idCuenta']],
                     ['debe'=>$det['debe'],'haber'=>$det['haber'],'descripcionD'=>$descripcionDT,'estado'=>'1']);
     
                 }          

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
        }
    
        public function insertarDetalle(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            try{            
                $ingreso->idFormula = $request->idFormula;
                $idVenta = $request->idVenta;
                $detalles = $request->data;
                foreach($detalles as $ep=>$det)
                {
                    $detalle = new DetalleVenta();
                    $detalle->idVenta= $idVenta;
                    $detalle->idProducto= $det['idProducto'];
                    $detalle->cantidad = $det['cantidad'];   
                    $detalle->precio = $det['precio'];
                    if($ingreso->idFormula){
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
            $ingreso = IngresoEgreso::findOrFail($request->id);
            $ingreso->estado = '0';
            $ingreso->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $ingreso = IngresoEgreso::findOrFail($request->id);
            $ingreso->estado = '1';
            $ingreso->save();
        }
           
    }