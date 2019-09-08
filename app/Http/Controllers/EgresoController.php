<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleEgreso;
use App\Venta;
use App\Compra;
use App\Ingreso;
use App\Egreso;

class EgresoController extends Controller
{
   
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');
    
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            if($buscar=='')
            {
                $egresos= Egreso::orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
            }
            else{
                    $egresos= Egreso::where('egresos.'.$criterio, 'like', '%'. $buscar . '%')
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
        public function listarEgreso(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $detalles = DetalleEgreso::join('cuentas','cuentas.id','detalle_egresos.idCuenta')
            ->where('detalle_egresos.idEgreso','=',$id) 
            ->where('detalle_egresos.estado','=','1')
            ->select('cuentas.id as idCuenta',DB::raw("concat(tipo,'.',nivel1,'.',nivel2,'.',nivel3,'.',nivel4)as codigo"),'cuentas.nombre as cuenta','debe','haber','orden','descripcionD')
            ->orderBy('orden','asc')
            ->get();
            return ['detalles'=>$detalles];
        }

        public function pdf(Request $request, $id)
        {
            $egreso= Egreso::join('cuentas','egresos.idCuenta','=','cuentas.id')
            ->select('egresos.id','idFormula',DB::raw("concat(cuentas.nombre,' ',cuentas.apellido) as cliente"),
            'fecha','pago','cantidad','descripcion','montoVenta','egresos.estado')
            ->where('egresos.id','=',$id)
            ->orderBy('egresos.id','desc')
            ->take(1)
            ->get();
    
            $detalles = DetalleVenta::join('egresos','egresos.id','=','detalle_egresos.idVenta')
            ->join('productos','productos.id','detalle_egresos.idProducto')
            ->select('productos.id as idProducto','productos.nombre as producto','detalle_egresos.cantidad','productos.unidad','detalle_egresos.precio','detalle_egresos.descripcionD')
            ->where('detalle_egresos.idVenta','=',$id)
            ->orderBy('detalle_egresos.idProducto','desc')
            ->get();
    
            $numventa= Egreso::select('egresos.id')->where('id',$id)->get();
            
            $pdf = \PDF::loadView('pdf.venta',['venta'=>$egreso,'detalles'=>$detalles]);
            return $pdf->download('venta_'.$numventa[0]->id.'.pdf');
    
        }

        
        public function store(Request $request)
        {   
            if (!$request->ajax()) return redirect('/');
            DB::beginTransaction();
            try{
                $year=date('Y');
                $factura=Egreso::whereYear('fecha','=',$year)->max('factura');
                
                $venta=Venta::max('registro');
                $ingreso=Ingreso::max('registro');
                $egreso=Egreso::max('registro');
                $compra=Compra::max('registro');
    
                $registro=$venta+$compra+$ingreso+$egreso;
    
                $mytime= Carbon::now('America/La_Paz');
                $egresos = new Egreso();
                $egresos->factura=$factura+1;
                $egresos->registro=$registro+1;
                $egresos->fecha = $mytime->toDateTimeString();
                $egresos->descripcion = $request->descripcion;
                $egresos->tipo ='Egresos';
                $egresos->monto=$request->monto;
                $egresos->estado = '1';
                $egresos->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
               $contar=0;
                foreach($detalles as $ep=>$det)
                {
                    $contar++;
                    $detalle = new DetalleEgreso();
                    $detalle->idEgreso= $egresos->id;
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
            $egreso = Egreso::findOrFail($request->id);
            $egreso->fecha = $mytime->toDateTimeString();
            $egreso->descripcion = $request->descripcion;
            $egreso->tipo ='Egresos';
            $egreso->monto=$request->monto;
            $egreso->estado = '1';
            $egreso->save();

            $detalles = $request->data;//Array de detalles
            $detalle = DetalleEgreso::where('idEgreso','=',$egreso->id)->update(['estado'=>'0']);
                 //Recorro todos los elementos
                 $contar=0;
            foreach($detalles as $ep=>$det)
            {         
            $contar++;              
            $detalleED=DetalleEgreso::updateOrInsert(['idEgreso' =>$egreso->id,'idCuenta'=>$det['idCuenta']],
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
            $egreso = Egreso::findOrFail($request->id);
            $egreso->estado = '0';
            $egreso->save();
        }
    
        public function activar(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $egreso = Egreso::findOrFail($request->id);
            $egreso->estado = '1';
            $egreso->save();
        }
           
    }