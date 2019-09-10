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
        if(!$request->ajax()) return redirect('/');
    
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            if($buscar=='')
            {
                $ingresos= Ingreso::orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
            }
            else{
                    $ingresos= Ingreso::where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
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
        public function pdf(Request $request, $id)
        {
            $ingreso=Ingreso::where('ingresos.id','=',$id)
            ->get();
    
            $detalles = DetalleIngreso::where('detalle_ingresos.idIngreso','=',$id)
            ->where('detalle_ingresos.estado','=','1')
            ->orderBy('detalle_ingresos.orden','asc')
            ->with('cuenta')
            ->get(); 
            $pdf = \PDF::loadView('ingreso.pdf.index',['ingreso'=>$ingreso,'detalles'=>$detalles]);
            return $pdf->download('ingreso_'.$id.'.pdf');
        }
        public function imprimir(Request $request, $id)
        {
            $ingreso=Ingreso::where('ingresos.id','=',$id)
            ->get();
    
            $detalles = DetalleIngreso::where('detalle_ingresos.idIngreso','=',$id)
            ->where('detalle_ingresos.estado','=','1')
            ->orderBy('detalle_ingresos.orden','asc')
            ->with('cuenta')
            ->get();       
                
            return view('ingreso.imprimir.index',['ingreso'=>$ingreso,'detalles'=>$detalles]);
        }
        public function listarIngreso(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $id = $request->id;
            $ingresos= Ingreso::where('ingresos.id','=',$id)
            ->get();
            $detalles = DetalleIngreso::join('cuentas','cuentas.id','detalle_ingresos.idCuenta')
            ->where('detalle_ingresos.idIngreso','=',$id) 
            ->where('detalle_ingresos.estado','=','1')
            ->select('cuentas.id as idCuenta',DB::raw("concat(tipo,'.',nivel1,'.',nivel2,'.',nivel3,'.',nivel4)as codigo"),'cuentas.nombre as cuenta','debe','haber','orden','descripcionD')
            ->orderBy('detalle_ingresos.orden','asc')
            ->get();
            return ['detalles'=>$detalles,'ingresos'=>$ingresos];
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

                $max=0;
                if($venta>$max)
                {
                    $max=$venta;
                }
                if($compra>$max)
                {
                    $max=$compra;
                }
                if($ingreso>$max)
                {
                    $max=$ingreso;
                }
                if($egreso>$max){
                    $max=$ingreso;
                }
                $registro=$max;
                
                $mytime= Carbon::now('America/La_Paz');
                $ingresos = new Ingreso();
                $ingresos->factura=$factura+1;
                $ingresos->registro=$registro+1;
                $ingresos->fecha = $mytime->toDateTimeString();
                $ingresos->descripcion = $request->descripcion;
                $ingresos->tipo ='Ingresos';
                $ingresos->monto=$request->monto;
                $ingresos->estado = '1';
                $ingresos->save();
    
                $detalles = $request->data;//Array de detalles
                //Recorro todos los elementos
                $i=0;
                foreach($detalles as $ep=>$det)
                {
                    $i++;
                    $detalle = new DetalleIngreso();
                    $detalle->idIngreso= $ingresos->id;
                    $detalle->idCuenta = $det['idCuenta'];
                    $detalle->orden=$i;
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
            $ingreso->tipo ='Ingresos';
            $ingreso->monto=$request->monto;
            $ingreso->estado = '1';
            $ingreso->save();

            $detalles = $request->data;//Array de detalles
            $detalle = DetalleIngreso::where('idIngreso','=',$ingreso->id)->update(['estado'=>'0']);
                 //Recorro todos los elementos
            $i=0;
            foreach($detalles as $ep=>$det)
            {
            $i++;               
            $detalleED=DetalleIngreso::updateOrInsert(['idIngreso' =>$ingreso->id,'idCuenta'=>$det['idCuenta']],
            ['orden'=>$i,'debe'=>$det['debe'],'haber'=>$det['haber'],'descripcionD'=>$det['descripcionD'],'estado'=>'1']);
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