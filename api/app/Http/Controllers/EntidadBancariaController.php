<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EntidadBancaria;

class EntidadBancariaController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function listarVigentes()
   {

       $entidades = EntidadBancaria::select('Codigo','RazonSocial','Siglas')->where('Vigencia','=',1)
                    ->orderBy('Siglas','ASC')
                    ->get();

       return response()->json($entidades,202);
   }

   
}
