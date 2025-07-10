<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->get('user');
        
        if (!$user || $user->rol !== 'admin') {
            return response()->json(['error' => 'Acceso denegado. Se requieren permisos de administrador'], 403);
        }
        
        return $next($request);
    }
}