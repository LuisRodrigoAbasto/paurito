<?php

namespace App\Http\Controllers\Api;

use App\Producto;
//use Illuminate\Support\Facades\DB;
use DB;
use Illuminate\Http\Request;
use Validator;

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
        $opcion = $request->opcion;
        $data = Producto::where($opcion, 'like', '%' . $buscar . '%')
        ->select('id', 'nombre', DB::raw('concat(stock," ",ref_stock) as stock'),
         DB::raw('concat(unidad," ",ref_unidad)as unidad'), 
         DB::raw("concat(floor(stock),' ',ref_unidad,' + ',truncate(((stock-floor(stock))*unidad),2),' ',ref_unidad) as total"), 'estado')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->responseOk($data, 'Datos Recuperados Correctamente');
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
    public function get($id){
        $table = Producto::findOrFail($id);
        return $this->responseOk($table,'Recuperado Exitosamente');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!$request->ajax()) {
        //     return redirect('/');
        // }
        DB::beginTransaction();
        try {

        $validator=Validator::make($request->all(),[
            'nombre'=>'required',
            'stock'=>['required','int','min:1'],
            'ref_stock'=>'required',
            'unidad'=>['required','int','min:1'],
            'ref_unidad'=>'required'
        ]);
        if($validator->fails()){
            return $this->responseError('Error de Validacion Producto',422,$validator->errors());
        }
        
        $table = new Producto();
        $input=$request->all();
        $table->fill($input);
        $table->estado="1";
        $table->save();
        DB::commit();
        return $this->responseOk($table,'Guardado Exitosamente');
    } catch (Exception $ex) {
        DB::rollBack();
        return $this->responseError('Error al Guardar el Producto',422);
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
        // if (!$request->ajax()) {
        //     return redirect('/');
        // }
        DB::beginTransaction();
        try {
        $validator=Validator::make($request->all(),[
            'id'=>'required',
            'nombre'=>'required',
            'stock'=>'required',
            'ref_stock'=>'required',
            'unidad'=>'required',
            'ref_unidad'=>'required'
        ]);
        if($validator->fails()){
            return $this->responseError('Error de Validacion Producto',422,$validator->errors());
        }
        
        $table = Producto::findOrFail($request->get('id'));
        $input=$request->all();
        $table->fill($input);
        $table->estado="1";
        $table->save();        
        DB::commit();
        return $this->responseOk($table,'Actualizado Exitosamente');
    } catch (Exception $ex) {
        DB::rollBack();
        return $this->responseError('Error al Actualizar el Producto',422);
    }
    }

    public function activar($id)
    {
        $table = Producto::findOrFail($id);
        if ($table == null) {
            return $this->responseError('No Existe el Producto', 422);
        }
        $table->estado = $table->estado?0:1;
        $table->save();

        return $this->responseOk(['ok'],($table->estado?'Activado':"Desactivado")." Exitosamente");
    }
    
}