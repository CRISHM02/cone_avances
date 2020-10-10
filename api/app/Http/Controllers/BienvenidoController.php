<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class BienvenidoController extends Controller
{
    public function getToken(){
        $factory = JWTFactory::customClaims([
            'sub' => env('API_id'),
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload);
        
        return response()->json($token->get(),200);
    }
}
