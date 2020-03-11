<?php

namespace App\Http\Controllers\Api;

use App\DetalleEgreso;
use App\Egreso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EgresoController extends ApiController
{

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $pagina = $request->pagina;
        $opcion = $request->opcion;

        $table = Egreso::where($opcion, 'like', '%' . $buscar . '%')
        ->orderBy('estado', 'desc')
        -> orderBy('id', 'desc')
        ->paginate($pagina);
        return $this->sendResponse($table, 'Datos Recuperados Correctamente');
    }
    public function listarEgreso(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;
        $egresos = Egreso::find($id);

        $egresos->detalles = DetalleEgreso::join('cuentas', 'cuentas.id', 'detalle_egresos.idCuenta')
            ->where('detalle_egresos.idEgreso', '=', $id)
            ->where('detalle_egresos.estado', '=', '1')
            ->select('cuentas.id as idCuenta', DB::raw("concat(nivel1,'.',nivel2,'.',nivel3,'.',nivel4,'.',nivel5)as codigo"), 'cuentas.nombre as cuenta', 'debe', 'haber', 'orden', 'descripcionD')
            ->orderBy('detalle_egresos.orden', 'asc')
            ->get();
        return ['egresos' => $egresos];
    }

    public function pdf(Request $request, $id)
    {
        $egreso = Egreso::find($id);

        $egreso->detalles = DetalleEgreso::where('detalle_egresos.idEgreso', '=', $id)
            ->where('detalle_egresos.estado', '=', '1')
            ->orderBy('detalle_egresos.orden', 'asc')
            ->with('cuenta')
            ->get();
        $pdf = \PDF::loadView('egreso.pdf.index', ['egreso' => $egreso]);
        return $pdf->download('egreso_' . $id . '.pdf');

    }
    public function imprimir(Request $request, $id)
    {
        $egreso = Egreso::find($id);

        $egreso->detalles = DetalleEgreso::where('detalle_egresos.idEgreso', '=', $id)
            ->where('detalle_egresos.estado', '=', '1')
            ->orderBy('detalle_egresos.orden', 'asc')
            ->with('cuenta')
            ->get();

        return view('egreso.imprimir.index', ['egreso' => $egreso]);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        DB::beginTransaction();
        try {
            $year = date('Y');
            $factura = Egreso::whereYear('fecha', '=', $year)->max('factura');
            $obj = new Libreria();
            $registro = $obj->registro_maximo();

            $mytime = Carbon::now();
            $table = new Egreso();
            $table->factura = $factura + 1;
            $table->registro = $registro + 1;
            $table->fecha = $mytime->toDateTimeString();
            $table->descripcion = $request->descripcion;
            $table->tipo = 'egresos';
            $table->monto = $request->monto;
            $table->estado = '1';
            $table->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos
            $i = 0;
            foreach ($detalles as $ep => $det) {
                $i++;
                $detalle = new DetalleEgreso();
                $detalle->egreso_id = $table->id;
                $detalle->cuenta_id = $det['cuenta_id'];
                $detalle->orden = $i;
                $detalle->debe = $det['debe'];
                $detalle->haber = $det['haber'];
                $detalle->descripcion = $det['descripcion'];
                $detalle->save();
            }
            DB::commit();
            return $this->sendResponse($table, 'Guardado Exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('Error al Guardar', 422);
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        DB::beginTransaction();
        try {

            $mytime = Carbon::now('America/La_Paz');
            $table = Egreso::findOrFail($request->id);
            $table->descripcion = $request->descripcion;
            $table->tipo = 'egresos';
            $table->monto = $request->monto;
            $table->estado = '1';
            $table->save();

            $detalles = $request->data; //Array de detalles
            $detalle = DetalleEgreso::where('egreso_id', '=', $table->id)->update(['estado' => '0']);
            //Recorro todos los elementos
            $i = 0;
            foreach ($detalles as $ep => $det) {
                $i++;
                $detalleED = DetalleEgreso::updateOrInsert(['egreso_id' => $table->id, 'cuenta_id' => $det['idCuenta']],
                    ['orden' => $i, 'debe' => $det['debe'], 'haber' => $det['haber'], 'descripcion' => $det['descripcion'], 'estado' => '1']);
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

        $table = Egreso::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['El Egreso No Existe'], 422);
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

        $table = Egreso::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Egreso No Existe'], 422);
        }
        $table->estado = '1';
        $table->save();

        return $this->sendResponse($table,'Activada Exitosamente');
    }


}
