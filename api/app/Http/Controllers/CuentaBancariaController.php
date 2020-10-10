<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CuentaBancaria;
use DB;

class CuentaBancariaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*
        SELECT cb.nombre,ef.siglas,cb.numeroCuenta,cb.CCI
from cuentabancaria cb
inner join entidadfinanciera ef on ef.codigo = cb.codigoEntidadFinanciera
        */
        $cuentas = DB::table('cuentabancaria as cb')->select('cb.Codigo','ef.siglas','cb.CCI','cb.numeroCuenta','cb.tipoMoneda','cb.nombre','cb.vigencia')
                                    ->join('entidadfinanciera as ef','ef.codigo','=','cb.codigoEntidadFinanciera')
                                    ->get();
        return response()->json($cuentas);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cuenta =  new CuentaBancaria();
        $cuenta->codigoEmpresa = $request->codEmpresa;
        $cuenta->codigoEntidadFinanciera = $request->codEntidad;
        $cuenta->CCI = $request->cci;
        $cuenta->numeroCuenta = $request->numCuenta;
        $cuenta->tipoMoneda = $request->tipoMoneda;
        $cuenta->nombre = $request->nombre;
        $cuenta->save();
        return response()->json($cuenta,202);

    }

    
    public function update(Request $request, $id)
    {
        $cuenta = CuentaBancaria::findOrFail($id);
        $cuenta->codigoEmpresa = $request->codEmpresa;
        $cuenta->codigoEntidadFinanciera = $request->codEntidad;
        $cuenta->CCI = $request->cci;
        $cuenta->numeroCuenta = $request->numCuenta;
        $cuenta->tipoMoneda = $request->tipoMoneda;
        $cuenta->nombre = $request->nombre;
        $cuenta->save();
        return response()->json($cuenta,202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cuenta = CuentaBancaria::findOrFail($id);
        $cuenta->vigencia = $cuenta->vigencia == 1 ? 0 : 1;
        $cuenta->save();
       return response()->json($cuenta,202);
    }
}
