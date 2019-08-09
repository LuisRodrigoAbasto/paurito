<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleIngreso;
use App\Venta;
use App\Compra;
use App\Ingreso;
use App\Egreso;

class IngresoController extends Controller
{
    public function index(Request $request){
        // if(!$request->ajax()) return redirect('/');
    
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            if($buscar=='')
            {
                $ingresos= Ingreso::where('tipo','=','1')->orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
            }
            else{
                    $ingresos= Ingreso::where('tipo','=','1')
                    ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
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
          
                $ingreso= Ingreso::join('cuentas','ingresos.idCuenta','=','cuentas.id')
                ->select('ingresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
                'fecha','pago','cantidad','descripcion','montoVenta','ingresos.estado')
                ->where('ingresos.id','=',$id)
                ->orderBy('ingresos.id','desc')
                ->take(1)
                ->get();
          
            return ['ingreso' => $ingreso];
            
        }
        public function obtenerDetalles(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $detalles = DetalleVenta::join('ingresos','ingresos.id','=','detalle_ingresos.idVenta')
            ->join('productos','productos.id','detalle_ingresos.idProducto')
            ->select('productos.id as idProducto','productos.nombre as producto','detalle_ingresos.cantidad','productos.unidad','detalle_ingresos.precio','detalle_ingresos.descripcionD')
            ->where('detalle_ingresos.idVenta','=',$id)
            ->orderBy('detalle_ingresos.idProducto','desc')
            ->get();
            return ['detalles'=>$detalles];
        }
        public function pdf(Request $request, $id)
        {
            $ingreso= Ingreso::join('cuentas','ingresos.idCuenta','=','cuentas.id')
            ->select('ingresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
            'fecha','pago','cantidad','descripcion','montoVenta','ingresos.estado')
            ->where('ingresos.id','=',$id)
            ->orderBy('ingresos.id','desc')
            ->take(1)
            ->get();
    
            $detalles = DetalleVenta::join('ingresos','ingresos.id','=','detalle_ingresos.idVenta')
            ->join('productos','productos.id','detalle_ingresos.idProducto')
            ->select('productos.id as idProducto','productos.nombre as producto','detalle_ingresos.cantidad','productos.unidad','detalle_ingresos.precio','detalle_ingresos.descripcionD')
            ->where('detalle_ingresos.idVenta','=',$id)
            ->orderBy('detalle_ingresos.idProducto','desc')
            ->get();
    
            $numventa= Ingreso::select('ingresos.id')->where('id',$id)->get();
            
            $pdf = \PDF::loadView('pdf.venta',['venta'=>$ingreso,'detalles'=>$detalles]);
            return $pdf->download('venta_'.$numventa[0]->id.'.pdf');
    
        }
        public function listarIngreso(Request $request)
        {
            // if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $detalles = DetalleIngreso::join('cuentas','cuentas.id','detalle_ingresos.idCuenta')
            ->where('detalle_ingresos.idIngreso','=',$id) 
            ->where('detalle_ingresos.estado','=','1')
            ->select('cuentas.id as idCuenta',DB::raw("concat(tipo,'.',nivel1,'.',nivel2,'.',nivel3,'.',nivel4)as codigo"),'cuentas.nombre as cuenta','debe','haber','orden','descripcionD')
            ->get();
            return ['detalles'=>$detalles];
        }
        
        public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                $year=date('Y');
                $factura=Ingreso::whereYear('fecha','=',$year)->max('factura');
                
    
                $venta=Venta::max('registro');
                $ingreso=Ingreso::max('registro');
                $egreso=Egreso::max('registro');
                $compra=Compra::max('registro');
    
                $registro=$venta+$compra+$ingreso+$egreso;
                
                $mytime= Carbon::now('America/La_Paz');
                $ingresos = new Ingreso();
                $ingresos->factura=$factura+1;
                $ingresos->registro=$registro+1;
                $ingresos->fecha = $mytime->toDateTimeString();
                $ingresos->descripcion = $request->descripcion;
                $ingresos->tipo ='1';
                $ingresos->monto=1000;
                $ingresos->estado = '1';
                $ingresos->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               $contar=0;
                foreach($detalles as $ep=>$det)
                {
                    $contar++;
                    $detalle = new DetalleIngreso();
                    $detalle->idIngreso= $ingreso->id;
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
            $ingreso = Ingreso::findOrFail($request->id);
            $ingreso->fecha = $mytime->toDateTimeString();
            $ingreso->descripcion = $request->descripcion;
            $ingreso->tipo ='1';
            $ingreso->estado = '1';
            $ingreso->save();

            $detalles = $request->data;//Array de detalles
            $detalle = DetalleIngreso::where('idIngreso','=',$ingreso->id)->update(['estado'=>'0']);
                 //Recorro todos los elementos
                 $contar=0;
            foreach($detalles as $ep=>$det)
            {         
            $contar++;              
            $detalleED=DetalleIngreso::updateOrInsert(['idIngreso' =>$ingreso->id,'idCuenta'=>$det['idCuenta']],
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
            $ingreso = Ingreso::findOrFail($request->id);
            $ingreso->estado = '0';
            $ingreso->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $ingreso = Ingreso::findOrFail($request->id);
            $ingreso->estado = '1';
            $ingreso->save();
        }
           
    }