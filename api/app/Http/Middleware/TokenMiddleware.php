<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        try {
            JWTAuth::setToken($token); // setToken and check
            if (!$claim = JWTAuth::getPayload()) {
                return response()->json(array('msg' => 'Token no encontrado'), 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(array('msg' => 'Token expirado'), 404);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(array('msg' => 'Token invalido'), 404);
        }
        return $next($request);
    }
}
