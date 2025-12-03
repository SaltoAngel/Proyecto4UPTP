<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (empty($roles)) {
            return $next($request);
        }

        if (!$request->user()) {
            return redirect ('/login');
        }

        foreach ($roles as $role) {
            if ($request->user()->roles->contains('nombre_rol', $role)) {
                return $next($request);
            };
        }

        return redirect('/')->with('error', "Acceso denegado");
    }
}
