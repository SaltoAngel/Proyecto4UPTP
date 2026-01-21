<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();

        // Si el usuario está pendiente y no está en las rutas de primer acceso
        if ($user->status === 'pendiente' &&
            !$request->routeIs('password.first_time_form') &&
            !$request->routeIs('password.first_time') &&
            !$request->routeIs('logout') &&
            !$request->routeIs('redirect.user')) {
            
            return redirect()->route('password.first_time_form');
        }
        
        // Permitir acceso normal a usuarios activos
        if ($user->status === 'activo') {
            return $next($request);
        }
    }

    return $next($request);
}
}