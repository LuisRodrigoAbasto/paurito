<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleCompra;
use App\Producto;
use App\Venta;
use App\Compra;
use App\Ingreso;
use App\Egreso;

class CompraController extends Controller
{
    public function index(Request $request)
    {
       // if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $compras= Compra::join('cuentas','compras.idProveedor','=','cuentas.id')
            ->select('compras.id','compras.factura','compras.registro','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoCompra','compras.estado')
            ->orderBy('compras.estado','desc')->orderBy('compras.id','desc')->paginate(5);
        }
        else{
            if($criterio=='descripcion'){
                $compras= Compra::join('cuentas','compras.idProveedor','=','cuentas.id')
                ->select('compras.id','compras.factura','compras.registro','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoCompra','compras.estado')
                ->where('compras.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('compras.estado','desc')
                ->orderBy('compras.id','desc')->paginate(5);
            }
            else{
                $compras= Compra::join('cuentas','compras.idProveedor','=','cuentas.id')
                ->select('compras.id','compras.factura','compras.registro','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoCompra','compras.estado')
                ->where('cuentas.nombre', 'like', '%'. $buscar . '%')
                ->orderBy('compras.estado','desc')
                ->orderBy('compras.id','desc')->paginate(5);
            }
            
        }
     
        return [
            'pagination' => [
                'total'        => $compras->total(),
                'current_page' => $compras->currentPage(),
                'per_page'     => $compras->perPage(),
                'last_page'    => $compras->lastPage(),
                'from'         => $compras->firstItem(),
                'to'           => $compras->lastItem(),
            ],
            'compras' => $compras
        ];
        
    }
    public function listarCompras(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $compras=Compra::join('cuentas','compras.idProveedor','=','cuentas.id')
        ->where('compras.id','=',$id)
        ->select('compras.id','compras.factura','compras.registro','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoCompra','compras.estado')
        ->get();

        $detalles = DetalleCompra::join('compras','compras.id','detalle_compras.idCompra')
        ->join('productos','productos.id','detalle_compras.idProducto')
        ->where('detalle_compras.idCompra','=',$id)
        ->select('productos.id as idProducto','productos.nombre as producto','detalle_compras.cantidad','productos.codigo','productos.unidad','productos.referencia','detalle_compras.precio')
        ->get();
        return ['compras'=>$compras,'detalles'=>$detalles];
    }
    public function store(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');
        DB::beginTransaction();
        try{
            $year=date('Y');
            $factura=Compra::whereYear('fecha','=',$year)->max('factura');

            $venta=Venta::max('registro');
            $ingreso=Ingreso::max('registro');
            $egreso=Egreso::max('registro');
            $compra=Compra::max('registro');

            $registro=$venta+$compra+$ingreso+$egreso;

            $mytime= Carbon::now('America/La_Paz');
            $compras = new Compra();
            $compras->factura=$factura+1;
            $compras->registro=$registro+1;
            $compras->idProveedor = $request->idProveedor;
            $compras->fecha = $mytime->toDateTimeString();
            $compras->cantidad=$request->cantidad;
            $compras->pago = $request->pago;
            $compras->montoCompra = $request->montoCompra;
            $compras->descripcion = $request->descripcion;
            $compras->tipo="Compras";
            $compras->estado = '1';
            $compras->save();

            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $contar=0;
            foreach($detalles as $ep=>$det)
            {
                $contar++;
                $detalle = new DetalleCompra();
                $detalle->idCompra= $compras->id;
                $detalle->idProducto= $det['idProducto'];
                $detalle->orden=$contar;
                $detalle->cantidad = $det['cantidad'];   
                $detalle->precio = $det['precio']; 
                $detalle->save();

                $producto = Producto::find($det['idProducto']);
                $producto->stock=$producto->stock+$detalle->cantidad;
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
        $compra = Compra::findOrFail($request->id);
        $compra->idProveedor = $request->idProveedor;
        $compra->fecha = $mytime->toDateTimeString();
        $compra->cantidad=$request->cantidad;
        $compra->pago = $request->pago;
        $compra->montoCompra= $request->montoCompra;
        $compra->descripcion = $request->descripcion;
        $compra->tipo="Compras";
        $compra->estado = '1';
        $compra->save();
        $detalle = DetalleCompra::where('idCompra','=',$compra->id)->update(['estado'=>'0']);

        $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
           $contar=0;
            foreach($detalles as $ep=>$det)
            {        
                $contar++;                
                $detalleED=DetalleCompra::updateOrInsert(['idCompra' =>$compra->id,'idProducto'=>$det['idProducto']]
                ,['orden'=>$contar,'cantidad'=>$det['cantidad'],'precio'=>$det['precio'],'estado'=>'1']);
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
        $compras = Compra::findOrFail($request->id);
        $compras->estado = '0';
        $compras->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $compras = Compra::findOrFail($request->id);
        $compras->estado = '1';
        $compras->save();
    }
       
}