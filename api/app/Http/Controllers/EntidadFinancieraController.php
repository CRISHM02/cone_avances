<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EntidadFinanciera;

class EntidadFinancieraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $entidades = EntidadFinanciera::select('Codigo','razonSocial','siglas')->where('vigencia','=',1)->get();

        return response()->json($entidades,202);
    }

    
}
