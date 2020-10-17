<?php

namespace App\Http\Controllers;

use App\empresa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class empresaController extends Controller
{
    public function inicio()
    {
        $empresa = DB::table('empresa as e')
            ->select('e.RUC', 'e.RazonSocial', 'e.Correo', 'e.Codigo','e.Vigencia')
            ->get();
        return response()->json($empresa, 200);
    }

    public function mostrarEmpresas()
    {
        return empresa::all();
    }

    public function tablaEmpresa()
    {
        return empresa::all('Codigo', 'RUC', 'RazonSocial', 'Correo', 'Vigencia');
    }

    public function mostrar($id)
    {
        return empresa::find($id);
    }

    public function registrar(Request $request)
    {
        $empresa = new empresa();

        $empresa->RazonSocial = $request->get('RazonSocial');
        $empresa->RUC = $request->get('RUC');
        $empresa->Facebook = $request->get('Facebook');
        $empresa->Instagram = $request->get('Instagram');
        $empresa->Youtube = $request->get('Youtube');
        $empresa->Whatsapp = $request->get('Whatsapp');
        $empresa->Correo = $request->get('Correo');
        if ($request->hasFile('Logo')) {
            $file = $request->file('Logo');
            $filename =  $request->get("RUC") . "." . $file->getClientOriginalExtension();
            $path = public_path() . '/logos/';
            $file->move($path, $filename);
            $empresa->Logo = $filename;
        }
        $empresa->save();


        return response()->json($empresa, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $empresa = empresa::findOrFail($id);
        $empresa->RazonSocial = $request->get('RazonSocial');
        $empresa->RUC = $request->get('RUC');
        $empresa->Facebook = $request->get('Facebook');
        $empresa->Instagram = $request->get('Instagram');
        $empresa->Youtube = $request->get('Youtube');
        $empresa->Whatsapp = $request->get('Whatsapp');
        $empresa->Correo = $request->get('Correo');
        if ($request->hasFile('Logo')) {
            $file = $request->file('Logo');
            $filename =  strtoupper($request->get("RUC")) . "." . $file->getClientOriginalExtension();
            $path = public_path() . '/logos/';
            $file->move($path, $filename);
            $empresa->Logo = $filename;
        }
        $empresa->save();
        return response()->json($empresa, 200);
    }

    public function eliminar($id)
    {
        $empresa = empresa::findOrFail($id);
        $empresa->Vigencia = 0;
        $empresa->save();

        return response()->json($empresa, 200);
    }

    public function cambiarEstado($id)
    {
        $empresa = empresa::findOrFail($id);
        $empresa->Vigencia = !$empresa->Vigencia;
        $empresa->save();

        return response()->json($empresa, 200);
    }
}
