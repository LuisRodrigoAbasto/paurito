<?php

namespace App\Http\Controllers\Api;

use App\Producto;
//use Illuminate\Support\Facades\DB;
use DB;
use Illuminate\Http\Request;

class ProductoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // // if (!$request->ajax()) {
        //     return redirect('/');
        // }

        $buscar = $request->buscar;
        $pagina = $request->pagina;
        $opcion = $request->opcion;
        $data = Producto::where($opcion, 'like', '%' . $buscar . '%')
        ->select('id', 'nombre', 'stock', 'unidad', 'codigo', DB::raw("floor(stock) as total,truncate(((stock-floor(stock))*codigo),2) as decimales"), 'referencia', 'estado')
            ->orderBy('id', 'desc')
            ->paginate($pagina);

        return $this->sendResponse($data, 'Datos Recuperados Correctamente');
    }
    public function notificaciones(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $productos = Producto::where('stock', '<=', '100')
            ->where('estado', '=', '1')
            ->get();

        return ['productos' => $productos];

    }
    public function listarProducto(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            $productos = Producto::where('estado', '=', '1')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
        } else {
            $productos = Producto::where($criterio, 'like', '%' . $buscar . '%')
                ->where('estado', '=', '1')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
        }

        return ['productos' => $productos];

    }
    public function listarPdf(Request $request)
    {
        $productos = Producto::select('id', 'nombre', 'stock', 'unidad', 'codigo',
            DB::raw("floor(stock) as total,truncate(((stock-floor(stock))*codigo),2) as decimales"), 'referencia', 'estado')
            ->where('estado', '=', '1')
            ->orderBy('estado', 'desc')->orderBy('id', 'desc')->get();

        $pdf = \PDF::loadView('producto.pdf.index', ['productos' => $productos]);
        return $pdf->download('productos.pdf');

//         $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();
    }
    public function listarImprimir(Request $request)
    {
        $productos = Producto::select('id', 'nombre', 'stock', 'unidad', 'codigo',
            DB::raw("floor(stock) as total,truncate(((stock-floor(stock))*codigo),2) as decimales"), 'referencia', 'estado')
            ->where('estado', '=', '1')
            ->orderBy('estado', 'desc')->orderBy('id', 'desc')->get();
        return view('producto.imprimir.index', ['productos' => $productos]);

//         $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();
    }
    public function selectProducto(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $filtro = $request->filtro;
        $productos = Producto::where('estado', '=', '1')
            ->where('nombre', 'like', '%' . $filtro . '%')
            ->orderBy('nombre', 'asc')
            ->limit(10)
            ->get();
        return ['productos' => $productos];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        DB::beginTransaction();
        try {

        $table = new Producto();
        $table->nombre = $request->nombre;
        $table->stock = $request->stock;
        $table->unidad = $request->unidad;
        $table->codigo = $request->codigo;
        $table->referencia = $request->referencia;
        $table->estado = '1';
        $table->save();
        
        DB::commit();
        return $this->sendResponse($table,'Guardado Exitosamente');
    } catch (Exception $e) {
        DB::rollBack();
        return $this->sendError('Error al Guardar',422);
    }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }
        DB::beginTransaction();
        try {

        $table = Producto::find($request->id);
        $table->nombre = $request->nombre;
        $table->stock = $request->stock;
        $table->unidad = $request->unidad;
        $table->codigo = $request->codigo;
        $table->referencia = $request->referencia;
        $table->estado = '1';
        $table->save();
        
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

        $table = Producto::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['Producto No Existe'], 422);
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

        $table = Producto::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['Producto No Existe'], 422);
        }
        $table->estado = '1';
        $table->save();

        return $this->sendResponse($table,'Activada Exitosamente');
    }
    
}