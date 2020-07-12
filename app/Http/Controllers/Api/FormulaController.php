<?php

namespace App\Http\Controllers\Api;

use App\DetalleFormula;
use App\Formula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormulaController extends ApiController
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) {
        //     return redirect('/');
        // }

        $buscar = $request->buscar;
        $pagina = $request->pagina;
        $opcion = $request->opcion;

        $table = Formula::where($opcion, 'like', '%' . $buscar . '%')
            // ->where('estado', '1')
            ->orderBy('id', 'desc')
            ->paginate($pagina);
        return $this->responseOk($table, 'Datos Recuperados Correctamente');
    }

    public function listarFormula(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;
        $detalles = DetalleFormula::join('formulas', 'formulas.id', 'detalle_formulas.idFormula')
            ->join('productos', 'productos.id', 'detalle_formulas.idProducto')
            ->where('detalle_formulas.idFormula', '=', $id)
            ->where('detalle_formulas.estado', '=', '1')
            ->select('detalle_formulas.idProducto', 'productos.nombre as producto', 'detalle_formulas.cantidad', 'productos.unidad', 'productos.referencia', 'productos.codigo')
            ->orderBy('detalle_formulas.orden', 'asc')
            ->get();
        return ['detalles' => $detalles];
    }
    public function pdf(Request $request, $id)
    {
        $formula = Formula::find($id);

        $formula->detalles = DetalleFormula::where('idFormula', '=', $id)
            ->where('detalle_formulas.estado', '=', '1')
            ->orderBy('detalle_formulas.orden', 'asc')
            ->with('producto')
            ->get();

        $pdf = \PDF::loadView('formula.pdf.index', ['formula' => $formula]);
        return $pdf->download('formula_' . $id . '.pdf');

    }
    public function imprimir(Request $request, $id)
    {
        $formula = Formula::find($id);

        $formula->detalles = DetalleFormula::where('idFormula', '=', $id)
            ->where('detalle_formulas.estado', '=', '1')
            ->orderBy('detalle_formulas.orden', 'asc')
            ->with('producto')
            ->get();
        return view('formula.imprimir.index', ['formula' => $formula]);
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        try {
            DB::beginTransaction();

            $table = new Formula();
            $table->nombre = $request->nombre;
            $table->cantidad = $request->cantidad;
            $table->estado = '1';
            $table->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos
            $i = 0;
            foreach ($detalles as $ep => $det) {
                $i++;
                $detalle = new DetalleFormula();
                $detalle->formula_id = $table->id;
                $detalle->orden = $i;
                $detalle->producto_id = $det['producto_id'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->save();
            }

         
            DB::commit();
            return $this->responseOk($table,'Guardado Exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError('Error al Guardar',422);
        }
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        try {
            DB::beginTransaction();
            $table = Formula::findOrFail($request->id);
            $table->nombre = $request->nombre;
            $table->cantidad = $request->cantidad;
            $table->estado = '1';
            $table->save();

            $detalle = DetalleFormula::where('idFormula', '=', $table->id)->update(['estado' => '0']);

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos
            $i = 0;
            foreach ($detalles as $ep => $det) {
                $i++;
                $detalleED = DetalleFormula::updateOrInsert(['idFormula' => $table->id,
                    'producto_id' => $det['producto_id']], ['orden' => $i, 'cantidad' => $det['cantidad'], 'estado' => '1']);
            }
            DB::commit();
            return $this->responseOk($table,'Actualizado Exitosamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError('Error al Actualizar',422);
        }
    }

    public function activar($id)
    {
        $table = Formula::findOrFail($id);
        if ($table == null) {
            return $this->responseError('No Existe La Formular', 422);
        }
        $table->estado = $table->estado?0:1;
        $table->save();

        return $this->responseOk(['ok'],($table->estado?'Activado':"Desactivado")." Exitosamente");
    }

}