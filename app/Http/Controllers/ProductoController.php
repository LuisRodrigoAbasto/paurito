<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Producto;
use DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $productos=Producto::select('id','nombre','stock','unidad','codigo',DB::raw("floor(stock) as total,truncate(((stock-floor(stock))*codigo),2) as decimales"),'estado')
            ->orderBy('id','desc')->paginate(5);
        }
        else{
            $productos = Producto::where($criterio, 'like','%'.$buscar.'%')->select('id','nombre','stock','unidad','codigo',DB::raw("floor(stock) as total,((stock-floor(stock))*codigo) as decimales"),'estado')->orderBy('id','desc')->paginate(5);
        }
     
        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
        
    }

    public function listarProducto(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $productos=Producto::where('estado','=','1')->orderBy('id','desc')->paginate(10);
        }
        else{
            $productos = Producto::where($criterio, 'like','%'.$buscar.'%')->where('estado','=','1')->orderBy('id','desc')->paginate(10);
        }
     
        return ['productos' => $productos];
        
    }
    public function listarPdf(Request $request)
    {
        $productos=Producto::select('id','nombre','stock','unidad','codigo',
        DB::raw("floor(stock) as total,truncate(((stock-floor(stock))*codigo),2) as decimales"),'estado')
        ->orderBy('estado','desc')->orderBy('id','desc')->get();

        $cont=Producto::count();
        $pdf = \PDF::loadView('pdf.productospdf',['productos'=>$productos,'cont'=>$cont]);
        return $pdf->download('productos.pdf');
    }
    public function selectProducto(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $productos = Producto::where('estado','=','1')
        ->Where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->get();
        return ['productos'=>$productos];
        
    }

    public function buscarProducto(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        if($filtro!=''){
        $productos = Producto::where('nombre','like','%'.$filtro.'%')
        ->select('id','nombre','stock','codigo','unidad')->orderBy('nombre','asc')->take(1)->get();
        return ['productos'=>$productos];}
        else{
            {
                $productos = Producto::where('estado','=','1')->get();
                return ['productos'=>$productos];}
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $productos = new Producto();
        $productos->nombre = $request->nombre;
        $productos->stock = $request->stock;
        $productos->codigo = $request->codigo;
        $productos->unidad = $request->unidad;
        $productos->estado = '1';
        $productos->save();
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
        if(!$request->ajax()) return redirect('/');
        $productos = Producto::findOrFail($request->id);
        $productos->nombre = $request->nombre;
        $productos->stock = $request->stock;
        $productos->codigo = $request->codigo;
        $productos->unidad = $request->unidad;
        $productos->estado = '1';
        $productos->save();
    }
    
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $productos = Producto::findOrFail($request->id);
        $productos->estado = '0';
        $productos->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $productos = Producto::findOrFail($request->id);
        $productos->estado = '1';
        $productos->save();
    }
       
}
