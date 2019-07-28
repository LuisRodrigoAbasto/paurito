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
        public function listarIngreso(Request $request)
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
                $ingreso = new IngresoEgreso();
                $ingreso->fecha = $mytime->toDateTimeString();
                $ingreso->descripcion = $request->descripcion;
                $ingreso->tipo ='1';
                $ingreso->estado = '1';
                $ingreso->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               $contar=0;
                foreach($detalles as $ep=>$det)
                {
                    $contar++;
                    $detalle = new DetalleIE();
                    $detalle->idIE= $ingreso->id;
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
            $ingreso = IngresoEgreso::findOrFail($request->id);
            $ingreso->fecha = $mytime->toDateTimeString();
            $ingreso->descripcion = $request->descripcion;
            $ingreso->tipo ='1';
            $ingreso->estado = '1';
            $ingreso->save();

            $detalles = $request->data;//Array de detalles
            $detalle = DetalleIE::where('idIE','=',$ingreso->id)->update(['estado'=>'0']);
                 //Recorro todos los elementos
                 $contar=0;
            foreach($detalles as $ep=>$det)
            {         
            $contar++;              
            $detalleED=DetalleIE::updateOrInsert(['idIE' =>$ingreso->id,'idCuenta'=>$det['idCuenta']],
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