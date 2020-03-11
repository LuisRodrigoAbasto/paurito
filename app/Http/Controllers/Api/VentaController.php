<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleVenta;
use App\Producto;
use App\Formula;
use App\Venta;
use App\Compra;
use App\Ingreso;
use App\Egreso;
use App\EnLetras;

class VentaController extends ApiController
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $pagina = $request->pagina;
        $opcion = $request->opcion;
            $table= Venta::join('cuentas','ventas.idCliente','=','cuentas.id')
            ->select('ventas.id','ventas.factura','ventas.registro','idFormula','cuentas.nombre','ventas.idCliente','fecha','pago','cantidad','descripcion','montoVenta','ventas.estado')
            ->where($opcion, 'like', '%' . $buscar . '%')
            ->where('ventas.estado','1')
            ->orderBy('ventas.id','desc')
            ->paginate($pagina);
        
            return $this->sendResponse($table, 'Datos Recuperados Correctamente');
        
    }
    public function pdf(Request $request, $id)
    {
        $venta= Venta::find($id);        

        $venta->detalles = DetalleVenta::where('detalle_ventas.idVenta','=',$id)
        ->where('detalle_ventas.estado','=','1')
        ->orderBy('detalle_ventas.orden','asc')
        ->get();

        $pdf = \PDF::loadView('venta.pdf.index',['venta'=>$venta]);
        return $pdf->download('venta_'.$id.'.pdf');

    }
    public function imprimir(Request $request, $id)
    {   
        $venta= Venta::find($id);
        $Obj=new EnLetras();
        $venta->letra=strtoupper($Obj->ValorEnLetras($venta->montoVenta,"BOLIVIANOS"));

        $venta->detalles = DetalleVenta::where('detalle_ventas.idVenta','=',$id)
        ->where('detalle_ventas.estado','=','1')
        ->orderBy('detalle_ventas.orden','asc')
        ->get();
        
        return view('venta.imprimir.index',['venta'=>$venta]);
    }
    public function listarVentas(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $ventas= Venta::join('cuentas','ventas.idCliente','=','cuentas.id')
        ->select('ventas.id','ventas.factura','ventas.registro','idFormula','cuentas.nombre','ventas.idCliente','fecha','pago','cantidad','descripcion','montoVenta','ventas.estado')
        ->where('ventas.id','=',$id)
        ->get();

        $detalles = DetalleVenta::join('ventas','ventas.id','=','detalle_ventas.idVenta')
        ->join('productos','productos.id','=','detalle_ventas.idProducto')
        ->where('detalle_ventas.idVenta','=',$id)
        ->where('detalle_ventas.estado','=','1') 
        ->select('detalle_ventas.idProducto','productos.nombre as producto','detalle_ventas.cantidad','productos.codigo','productos.unidad','productos.referencia','detalle_ventas.precio','detalle_ventas.descripcionD')
        ->orderBy('detalle_ventas.orden','asc')
        ->get();

        $formula=Formula::where('estado','=','1')->where('id','=',$ventas[0]->idFormula)
        ->get();

        return ['ventas'=>$ventas,'detalles'=>$detalles,'formula'=>$formula];
    }
    
    public function store(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');
        DB::beginTransaction();
        try{            
            $year=date('Y');
            $factura=Venta::whereYear('fecha','=',$year)->max('factura');
            $obj=new Libreria();
            $registro = $obj->registro_maximo();

            $mytime= Carbon::now();
            $table = new Venta();
            $table->factura=$factura+1;
            $table->registro=$registro+1;
            $table->idCliente = $request->idCliente;
            $table->idFormula =$request->idFormula;
            $table->fecha = $mytime->toDateTimeString();
            $table->pago = $request->pago;
            $table->cantidad = $request->cantidad;
            $table->montoVenta = $request->montoVenta;
            $table->descripcion = $request->descripcion;
            $table->tipo = 'Ventas';
            $table->estado = '1';
            $table->save();

            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $i=0;
            foreach($detalles as $ep=>$det)
            {
                $i++;
                $detalle = new DetalleVenta();
                $detalle->idVenta= $ventas->id;
                $detalle->idProducto= $det['idProducto'];
                $detalle->orden=$i;
                $detalle->cantidad = $det['cantidad'];   
                $detalle->precio = $det['precio']; 
                $detalle->descripcionD = '';
                if($ventas->idFormula==0){
                    $detalle->descripcionD = $det['descripcionD'];
                }
                $detalle->save();

                $producto = Producto::find($det['idProducto']);
                $producto->stock=$producto->stock-$detalle->cantidad;
                $producto->save();
            } 
            DB::commit();
            return $this->sendResponse($table,'Guardado Exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('Error al Guardar',422);
        }
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        DB::beginTransaction();  
        try{      
        $mytime= Carbon::now('America/La_Paz');
        $table = Venta::findOrFail($request->id);
        $table->idCliente = $request->idCliente;
        $table->idFormula = $request->idFormula;
        $table->pago = $request->pago;
        $table->cantidad = $request->cantidad;
        $table->descripcion = $request->descripcion;
        $table->montoVenta = $request->montoVenta;
        $table->tipo = 'Ventas';
        $table->estado = '1';
        $table->save();

        $detalle = DetalleVenta::where('venta_id','=',$table->id)->update(['estado'=>'0']);

       $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $i=0;
            foreach($detalles as $ep=>$det)
            {
                $i++;
                $descripcion =null;
                if($venta->idFormula==0){
                    $descripcion = $det['descripcion'];
                    if($descripcion==''){
                        $descripcion =null;
                    }
                }
                $detalleED=DetalleVenta::updateOrInsert(['venta_id' =>$table->id,'idProducto'=>$det['idProducto']],
                ['orden'=>$i,'cantidad'=>$det['cantidad'],'precio'=>$det['precio'],'descripcionD'=>$descripcionDT,'estado'=>'1']);
            }          
            DB::commit();
            return $this->sendResponse($table,'Actualizado Exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('Error al Actualizar',422);
        }
    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $table = Venta::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Venta No Existe'], 422);
        }
        $table->estado = '0';
        $table->save();

        return $this->sendResponse($table,'Desactivada Exitosamente');
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $table = Venta::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Venta No Existe'], 422);
        }
        $table->estado = '1';
        $table->save();

        return $this->sendResponse($table,'Activada Exitosamente');
    }
    
}