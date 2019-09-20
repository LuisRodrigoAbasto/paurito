<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Formula;
use App\DetalleFormula;

class FormulaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $formulas=Formula::orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
        }
        else{
            $formulas = Formula::where($criterio, 'like','%'.$buscar.'%')->orderBy('estado','desc')->orderBy('id','desc')->paginate(5);
        }
     
        return [
            'pagination' => [
                'total'        => $formulas->total(),
                'current_page' => $formulas->currentPage(),
                'per_page'     => $formulas->perPage(),
                'last_page'    => $formulas->lastPage(),
                'from'         => $formulas->firstItem(),
                'to'           => $formulas->lastItem(),
            ],
            'formulas' => $formulas
        ];
        
    }
    public function selectFormula(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $formulas = Formula::where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->limit(10)
        ->get();
        return ['formulas'=>$formulas];
        
    }
    public function listarFormula(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $detalles = DetalleFormula::join('formulas','formulas.id','detalle_formulas.idFormula')
        ->join('productos','productos.id','detalle_formulas.idProducto')
        ->where('detalle_formulas.idFormula','=',$id) 
        ->where('detalle_formulas.estado','=','1')
        ->select('detalle_formulas.idProducto','productos.nombre as producto','detalle_formulas.cantidad','productos.unidad','productos.referencia','productos.codigo')
        ->orderBy('detalle_formulas.orden','asc')
        ->get();
        return ['detalles'=>$detalles];
    }
    public function pdf(Request $request, $id)
    {
        $formula=Formula::find($id);

        $formula->detalles =DetalleFormula::where('idFormula','=',$id)
        ->where('detalle_formulas.estado','=','1')
        ->orderBy('detalle_formulas.orden','asc')
        ->with('producto')
        ->get();

        $pdf = \PDF::loadView('formula.pdf.index',['formula'=>$formula]);
        return $pdf->download('formula_'.$id.'.pdf');

    }
    public function imprimir(Request $request, $id)
    {
        $formula=Formula::find($id);

        $formula->detalles =DetalleFormula::where('idFormula','=',$id)
        ->where('detalle_formulas.estado','=','1')
        ->orderBy('detalle_formulas.orden','asc')
        ->with('producto')
        ->get();
        return view('formula.imprimir.index',['formula'=>$formula]);
    }
    public function store(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $formula = new Formula();
            $formula->nombre = $request->nombre;
            $formula->cantidad=$request->cantidad;
            $formula->estado = '1';
            $formula->save();

            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $i=0;
            foreach($detalles as $ep=>$det)
            {
                $i++;
                $detalle = new DetalleFormula();
                $detalle->idFormula= $formula->id;
                $detalle->orden=$i;
                $detalle->idProducto= $det['idProducto'];
                $detalle->cantidad = $det['cantidad'];   
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
        $formula = Formula::findOrFail($request->id);
        $formula->nombre = $request->nombre;
        $formula->cantidad=$request->cantidad;
        $formula->estado = '1';
        $formula->save();

        $detalle = DetalleFormula::where('idFormula','=',$formula->id)->update(['estado'=>'0']);
        
       $detalles = $request->data;//Array de detalles
       //Recorro todos los elementos
       $i=0;
       foreach($detalles as $ep=>$det)
       {
           $i++;                   
           $detalleED=DetalleFormula::updateOrInsert(['idFormula' =>$formula->id,
           'idProducto'=>$det['idProducto']],['orden'=>$i,'cantidad'=>$det['cantidad'],'estado'=>'1']);
       }          
        DB::commit();
    } catch (Exception $e){
        DB::rollBack();
    }
    }
   
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $formulas = Formula::findOrFail($request->id);
        $formulas->estado = '0';
        $formulas->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $formulas = Formula::findOrFail($request->id);
        $formulas->estado = '1';
        $formulas->save();
    }
       
}