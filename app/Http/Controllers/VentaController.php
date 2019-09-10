<?php

namespace App\Http\Controllers;

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

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $ventas= Venta::join('cuentas','ventas.idCliente','=','cuentas.id')
            ->select('ventas.id','ventas.factura','ventas.registro','idFormula','cuentas.nombre','ventas.idCliente','fecha','pago','cantidad','descripcion','montoVenta','ventas.estado')
            ->orderBy('ventas.estado','desc')->orderBy('ventas.id','desc')->paginate(5);
        }
        else{
            $ventas= Venta::join('cuentas','ventas.idCliente','=','cuentas.id')
            ->select('ventas.id','ventas.factura','ventas.registro','idFormula','cuentas.nombre','ventas.idCliente','fecha','pago','cantidad','descripcion','montoVenta','ventas.estado')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ventas.estado','desc')
            ->orderBy('ventas.id','desc')->paginate(5);
        }
     
        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
        
    }
    public function pdf(Request $request, $id)
    {
        $venta= Venta::where('ventas.id','=',$id)
        ->with('cuenta')
        ->get();

        $detalles = DetalleVenta::where('detalle_ventas.idVenta','=',$id)
        ->where('detalle_ventas.estado','=','1')
        // ->with('producto')
        ->orderBy('detalle_ventas.orden','asc')
        ->get();

        $pdf = \PDF::loadView('venta.pdf.index',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('venta_'.$id.'.pdf');

    }
    public function imprimir(Request $request, $id)
    {   
        $venta= Venta::where('ventas.id','=',$id)
        ->with('cuenta')
        ->get();

        $detalles = DetalleVenta::where('detalle_ventas.idVenta','=',$id)
        ->where('detalle_ventas.estado','=','1')
        ->with('producto')
        ->orderBy('detalle_ventas.orden','asc')
        ->get();
        
        return view('venta.imprimir.index',['venta'=>$venta,'detalles'=>$detalles]);
    }
    public function listarVentas(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $ventas= Venta::join('cuentas','ventas.idCliente','=','cuentas.id')
        ->where('ventas.id','=',$id)
        ->select('ventas.id','ventas.factura','ventas.registro','idFormula','cuentas.nombre','ventas.idCliente','fecha','pago','cantidad','descripcion','montoVenta','ventas.estado')
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
            $ventas = new Venta();
            $ventas->factura=$factura+1;
            $ventas->registro=$registro+1;
            $ventas->idCliente = $request->idCliente;
            $ventas->idFormula =$request->idFormula;
            $ventas->fecha = $mytime->toDateTimeString();
            $ventas->pago = $request->pago;
            $ventas->cantidad = $request->cantidad;
            $ventas->montoVenta = $request->montoVenta;
            $ventas->descripcion = $request->descripcion;
            $ventas->tipo = 'Ventas';
            $ventas->estado = '1';
            $ventas->save();

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
        $venta = Venta::findOrFail($request->id);
        $venta->idCliente = $request->idCliente;
        $venta->idFormula = $request->idFormula;
        $venta->fecha = $mytime->toDateTimeString();
        $venta->pago = $request->pago;
        $venta->cantidad = $request->cantidad;
        $venta->descripcion = $request->descripcion;
        $venta->montoVenta = $request->montoVenta;
        $venta->tipo = 'Ventas';
        $venta->estado = '1';
        $venta->save();

        $detalle = DetalleVenta::where('idVenta','=',$venta->id)->update(['estado'=>'0']);

       $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $i=0;
            foreach($detalles as $ep=>$det)
            {
                $i++;
                $descripcionDT =null;
                if($venta->idFormula==0){
                    $descripcionDT = $det['descripcionD'];
                    if($descripcionDT==''){
                        $descripcionDT =null;
                    }
                }
                $detalleED=DetalleVenta::updateOrInsert(['idVenta' =>$venta->id,'idProducto'=>$det['idProducto']],
                ['orden'=>$i,'cantidad'=>$det['cantidad'],'precio'=>$det['precio'],'descripcionD'=>$descripcionDT,'estado'=>'1']);
            }          
        DB::commit();    
    }
     catch (Exception $e){
        DB::rollBack();
    }
    }
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $ventas = Venta::findOrFail($request->id);
        $ventas->estado = '0';
        $ventas->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $ventas = Venta::findOrFail($request->id);
        $ventas->estado = '1';
        $ventas->save();
    }
       
}