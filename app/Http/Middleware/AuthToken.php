<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        
        if (!$token) {
            return response()->json(['error' => 'Token de autorización requerido'], 401);
        }
        
        $decoded = base64_decode($token);
        $parts = explode(':', $decoded);
        
        if (count($parts) !== 2 || !is_numeric($parts[0])) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
        
        $usuario = Usuario::find($parts[0]);
        
        if (!$usuario) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
        
        // Agregar el usuario a la request para que esté disponible en los controladores
        $request->merge(['user_id' => $usuario->id, 'user' => $usuario]);
        
        return $next($request);
    }
}