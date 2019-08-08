<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Compra;
use App\DetalleCompra;

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
            ->select('compras.id','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoCompra','compras.estado')
            ->orderBy('compras.estado','desc')->orderBy('compras.id','desc')->paginate(5);
        }
        else{
            if($criterio=='descripcion'){
                $compras= Compra::join('cuentas','compras.idProveedor','=','cuentas.id')
                ->select('compras.id','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoCompra','compras.estado')
                ->where('compras.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('compras.estado','desc')
                ->orderBy('compras.id','desc')->paginate(5);
            }
            else{
                $compras= Compra::join('cuentas','compras.idProveedor','=','cuentas.id')
                ->select('compras.id','idFormula','cuentas.nombre','compras.idProveedor','fecha','pago','cantidad','descripcion','montoVenta','compras.estado')
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
        $detalles = DetalleCompra::join('compras','compras.id','detalle_compras.idCompra')
        ->join('productos','productos.id','detalle_compras.idProducto')
        ->where('detalle_compras.idCompra','=',$id)
        ->where('detalle_compras.estado','=','1')
        ->select('productos.id as idProducto','productos.nombre as producto','detalle_compras.cantidad','productos.codigo','productos.unidad','productos.referencia','detalle_compras.precio')
        ->get();
        return ['detalles'=>$detalles];
    }
    public function store(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();
            $mytime= Carbon::now('America/La_Paz');
            $compra = new Compra();
            $compra->idProveedor = $request->idProveedor;
            $compra->fecha = $mytime->toDateTimeString();
            $compra->cantidad=$request->cantidad;
            $compra->pago = $request->pago;
            $compra->montoCompra = $request->montoCompra;
            $compra->descripcion = $request->descripcion;
            $compra->estado = '1';
            $compra->save();

            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $contar=0;
            foreach($detalles as $ep=>$det)
            {
                $contar++;
                $detalle = new DetalleCompra();
                $detalle->idCompra= $compra->id;
                $detalle->idProducto= $det['idProducto'];
                $detalle->orden=$contar;
                $detalle->cantidad = $det['cantidad'];   
                $detalle->precio = $det['precio']; 
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
        $compra = Compra::findOrFail($request->id);
        $compra->idProveedor = $request->idProveedor;
        $compra->fecha = $mytime->toDateTimeString();
        $compra->cantidad=$request->cantidad;
        $compra->pago = $request->pago;
        $compra->montoCompra= $request->montoCompra;
        $compra->descripcion = $request->descripcion;
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