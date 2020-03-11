<?php

namespace App\Http\Controllers\Api;

use App\Compra;
use App\DetalleCompra;
use App\Egreso;
use App\Ingreso;
use App\Producto;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lib\Libreria;

class CompraController extends ApiController
{
    public function index(Request $request)
    {
        //    if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $pagina = $request->pagina;
        $opcion = $request->opcion;

        $table = Compra::join('cuentas', 'compras.proveedor_id', '=', 'cuentas.id')
            ->where('compras.estado', '1')
            ->where($opcion, 'like', '%' . $buscar . '%')
            ->orderBy('compras.id', 'desc')
            ->paginate($pagina);

        return $this->sendResponse($table, 'Datos Recuperados Correctamente');
    }
    public function pdf(Request $request, $id)
    {
        $table = Compra::with('cuenta')->find($id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Compra No Existe'], 422);
        }

        $table->detalles = DetalleCompra::where('detalle_compras.compra_id', '=', $id)
            ->orderBy('detalle_compras.orden', 'asc')
            ->where('detalle_compras.estado', '=', '1')
            ->with('producto')
            ->get();

        $pdf = \PDF::loadView('compra.pdf.index', ['compra'=>$table]);
        return $pdf->download('compra_' . $id . '.pdf');

    }
    public function imprimir($id)
    {
        $table = Compra::find($id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Compra No Existe'], 422);
        }

        $table->detalles = DetalleCompra::where('detalle_compras.compra_id', '=', $id)
            ->where('detalle_compras.estado', '=', '1')
            ->orderBy('detalle_compras.orden', 'asc')
            ->get();

        return view('compra.imprimir.index', ['compra' => $table]);
    }
    public function listarCompras(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $table = Compra::find($id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Compra No Existe'], 422);
        }

        $table->detalles = DetalleCompra::where('detalle_compras.compra_id', $id)
            ->where('detalle_compras.estado', '1')
            ->orderBy('detalle_compras.orden', 'asc')
            ->get();
            return $this->sendResponse($table, 'Datos Recuperados Correctamente');
    }
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        DB::beginTransaction();
        try {
            $year = date('Y');
            $factura = Compra::whereYear('fecha', '=', $year)->max('factura');
            $obj=new Libreria();
            $registro = $obj->registro_maximo();

            $mytime = Carbon::now();
            $table = new Compra();
            $table->factura = $factura + 1;
            $table->registro = $registro + 1;
            $table->proveedor_id = $request->proveedor_id;
            $table->fecha = $mytime->toDateTimeString();
            // $table->cantidad = $request->cantidad;
            $table->pago = $request->pago;
            $table->monto_total = $request->monto_total;
            $table->descripcion = $request->descripcion;
            $table->tipo = "Compras";
            $table->estado = '1';
            $table->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos
            $i = 0;
            foreach ($detalles as $ep => $det) {
                $i++;
                $detalle = new DetalleCompra();
                $detalle->compra_id = $table->id;
                $detalle->producto_id = $det['producto_id'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->orden = $i;
                $detalle->save();

                $producto = Producto::find($det['idProducto']);
                $producto->stock = $producto->stock + $detalle->cantidad;
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
        if (!$request->ajax()) {
            return redirect('/');
        }

        DB::beginTransaction();
        try {
            $mytime = Carbon::now();
            $table = Compra::find($request->id);
            if ($table == null) {
                return $this->sendError('Error de Datos', ['La Compra No Existe'], 422);
            }
            $table->proveedor_id = $request->proveedor_id;
            // $table->cantidad = $request->cantidad;
            $table->pago = $request->pago;
            $table->monto_total = $request->monto_total;
            $table->descripcion = $request->descripcion;
            $table->estado = '1';
            $table->save();
            $detalle = DetalleCompra::where('compra_id', '=', $table->id)->update(['estado' => '0']);

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos
            $i = 0;
            foreach ($detalles as $ep => $det) {
                $i++;
                $detalleED = DetalleCompra::updateOrInsert(['compra_id' => $table->id, 'idProducto' => $det['idProducto']]
                    , ['orden' => $i, 'cantidad' => $det['cantidad'], 'precio' => $det['precio'], 'estado' => '1']);
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

        $table = Compra::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Compra No Existe'], 422);
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

        $table = Compra::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Compra No Existe'], 422);
        }
        $table->estado = '1';
        $table->save();

        return $this->sendResponse($table,'Activada Exitosamente');
    }
    
}