<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\IngresoEgreso;
use App\DetalleIE;

class EgresoController extends Controller
{
   
    public function index(Request $request){
        // if(!$request->ajax()) return redirect('/');
    
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            if($buscar=='')
            {
                $egresos= IngresoEgreso::where('tipo','=','2')->orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
            }
            else{
                    $egresos= IngresoEgreso::where('tipo','=','2')
                    ->where('ingreso_egresos.'.$criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('estado','desc')
                    >orderBy('id','desc')->paginate(5);
            }
         
            return [
                'pagination' => [
                    'total'        => $egresos->total(),
                    'current_page' => $egresos->currentPage(),
                    'per_page'     => $egresos->perPage(),
                    'last_page'    => $egresos->lastPage(),
                    'from'         => $egresos->firstItem(),
                    'to'           => $egresos->lastItem(),
                ],
                'egresos' => $egresos
            ];
            
        }
        public function obtenerEgreso(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
    
            $id = $request->id;
          
                $egreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
                ->select('ingresos_egresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
                'fecha','pago','cantidad','descripcion','montoVenta','ingresos_egresos.estado')
                ->where('ingresos_egresos.id','=',$id)
                ->orderBy('ingresos_egresos.id','desc')
                ->take(1)
                ->get();
          
            return ['egreso' => $egreso];
            
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
            $egreso= IngresoEgreso::join('cuentas','ingresos_egresos.idCuenta','=','cuentas.id')
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
            
            $pdf = \PDF::loadView('pdf.venta',['venta'=>$egreso,'detalles'=>$detalles]);
            return $pdf->download('venta_'.$numventa[0]->id.'.pdf');
    
        }
        public function listarEgreso(Request $request)
        {
            // if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $detalles = DetalleIE::join('cuentas','cuentas.id','detalle_ingresos_egresos.idCuenta')
            ->where('detalle_ingresos_egresos.idIE','=',$id) 
            ->where('detalle_ingresos_egresos.estado','=','1')
            ->select('cuentas.id as idCuenta',DB::raw("concat(tipo,'.',nivel1,'.',nivel2,'.',nivel3,'.',nivel4)as codigo"),'cuentas.nombre as cuenta','debe','haber','orden','descripcionD')
            ->get();
            return ['detalles'=>$detalles];
        }
        
        public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            try{
                DB::beginTransaction();
                $mytime= Carbon::now('America/La_Paz');
                $egreso = new IngresoEgreso();
                $egreso->fecha = $mytime->toDateTimeString();
                $egreso->descripcion = $request->descripcion;
                $egreso->tipo ='2';
                $egreso->estado = '1';
                $egreso->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               $contar=0;
                foreach($detalles as $ep=>$det)
                {
                    $contar++;
                    $detalle = new DetalleIE();
                    $detalle->idIE= $egreso->id;
                    $detalle->idCuenta = $det['idCuenta'];
                    $detalle->orden=$contar;
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
            DB::beginTransaction();  
            try{
           
            $mytime= Carbon::now('America/La_Paz');
            $egreso = IngresoEgreso::findOrFail($request->id);
            $egreso->fecha = $mytime->toDateTimeString();
            $egreso->descripcion = $request->descripcion;
            $egreso->tipo ='2';
            $egreso->estado = '1';
            $egreso->save();

            $detalles = $request->data;//Array de detalles
            $detalle = DetalleIE::where('idIE','=',$egreso->id)->update(['estado'=>'0']);
                 //Recorro todos los elementos
                 $contar=0;
            foreach($detalles as $ep=>$det)
            {         
            $contar++;              
            $detalleED=DetalleIE::updateOrInsert(['idIE' =>$egreso->id,'idCuenta'=>$det['idCuenta']],
            ['orden'=>$contar,'debe'=>$det['debe'],'haber'=>$det['haber'],'descripcionD'=>$det['descripcionD'],'estado'=>'1']);
            }          

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
        }

        public function desactivar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $egreso = IngresoEgreso::findOrFail($request->id);
            $egreso->estado = '0';
            $egreso->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $egreso = IngresoEgreso::findOrFail($request->id);
            $egreso->estado = '1';
            $egreso->save();
        }
           
    }