<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cuenta;

class CuentaController extends ApiController
{
    public function index(Request $request)
    {
        // if(!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $pagina = $request->pagina;
        $opcion = $request->opcion;
        $data=Cuenta::where($opcion, 'like', '%' . $buscar . '%')
        ->where('estado','1')
        ->orderBy('nivel1','asc')
        ->orderBy('nivel2','asc')
        ->orderBy('nivel3','asc')
        ->orderBy('nivel4','asc')
        ->orderBy('nivel5','asc')
        ->paginate($pagina);

        return $this->sendResponse($data, 'Datos Recuperados Correctamente');
    
    }
    public function buscarCuenta(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idCuenta=$request->idCuenta;
        
        if($idCuenta==0){
        $cuentas = Cuenta::where('nivel','=','1')
        ->count('id');
        }
        else{
            $cuentas = Cuenta::where('idCuenta','=',$idCuenta)
        ->count('id');
            }
            $cuentas++;
        return ['cuentas'=>$cuentas];
    }
    public function listarPdf(Request $request)
    {
        $cuentas=Cuenta::where('estado','=','1')
        ->orderBy('nivel1','asc')
        ->orderBy('nivel2','asc')
        ->orderBy('nivel3','asc')
        ->orderBy('nivel4','asc')
        ->orderBy('nivel5','asc')
        ->get();
        $pdf = \PDF::loadView('cuenta.pdf.index',['cuentas'=>$cuentas]);
        return $pdf->download('cuentas.pdf');
        // return $pdf->stream();
    }
    public function listarImprimir(Request $request)
    {
        $cuentas=Cuenta::where('estado','=','1')
        ->orderBy('nivel1','asc')
        ->orderBy('nivel2','asc')
        ->orderBy('nivel3','asc')
        ->orderBy('nivel4','asc')
        ->orderBy('nivel5','asc')
        ->get();

        // $pdf = App::make('dompdf.wrapper');
        return view('cuenta.imprimir.index',['cuentas'=>$cuentas]);
    }
    
    public function cuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $table = Cuenta::where('estado','=','1')
        ->where('nivel','<','5')
        ->where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->get();
        return $this->sendResponse($table, 'Datos Recuperados Correctamente');
    }
    public function selectCuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $table = Cuenta::where('estado','=','1')
        ->where('nivel','=','5')
        ->where('nombre','like','%'.$filtro.'%')
        ->orderBy('nombre','asc')
        ->limit(10)
        ->get();
        return $this->sendResponse($table, 'Datos Recuperados Correctamente');
    }
    public function listarCuenta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $table = Cuenta::where('estado','=','1')
        ->where('nombre','like','%'.$filtro.'%')
        ->where('nivel','=','5')
        ->orderBy('nombre','asc')
        ->limit(10)
        ->get();
        return $this->sendResponse($table, 'Datos Recuperados Correctamente');
    }
   
    public function store(Request $request)
    {

        // if(!$request->ajax()) return redirect('/');
        DB::beginTransaction();
        try {
        $table = new Cuenta();
        $table->nombre = $request->nombre;
        $table->telefono = $request->telefono;
        $table->empresa = $request->empresa;
        $table->direccion = $request->direccion;
        $table->nivel=$request->nivel;
        $table->cuenta_id=$request->cuenta_id;

        if($table->idCuenta==0)
        {
            $table->idCuenta=null;
        }
        $table->nivel1 = $request->nivel1;
        $table->nivel2 = $request->nivel2;
        $table->nivel3 = $request->nivel3;
        $table->nivel4 = $request->nivel4;
        $table->nivel5 = $request->nivel5;
        $table->estado = '1';
        $table->save();
        DB::commit();
        return $this->sendResponse($table,'Guardado Exitosamente');
    } catch (Exception $e) {
        DB::rollBack();
        return $this->sendError('Error al Guardar',422);
    }
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        DB::beginTransaction();
        try {
        $table = Cuenta::findOrFail($request->id);
        $table->nombre = $request->nombre;
        $table->telefono = $request->telefono;
        $table->empresa = $request->empresa;
        $table->direccion = $request->direccion;
        $table->nivel=$request->nivel;
        $table->nivel1 = $request->nivel1;
        $table->nivel2 = $request->nivel2;
        $table->nivel3 = $request->nivel3;
        $table->nivel4 = $request->nivel4;
        $table->nivel5 = $request->nivel5;
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

        $table = Cuenta::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Cuenta No Existe'], 422);
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

        $table = Cuenta::find($request->id);
        if ($table == null) {
            return $this->sendError('Error de Datos', ['La Cuenta No Existe'], 422);
        }
        $table->estado = '1';
        $table->save();

        return $this->sendResponse($table,'Activada Exitosamente');
    }


}
