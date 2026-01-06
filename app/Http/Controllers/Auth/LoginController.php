<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;    
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Solo en desarrollo: auto-logout si está autenticado
        if (app()->environment('local') && Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            
            return redirect()->route('login')->with('info', 'Sesión anterior cerrada automáticamente.');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // Intentar autenticación
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            
            // Para AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                ], 422);
            }
            
            // Para POST tradicional
            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
        }

        // Autenticación exitosa
        $user = Auth::user();
        $request->session()->regenerate();

        // Verificar estado del usuario
        if ($user->status !== 'activo') {
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta está ' . $user->status
                ], 422);
            }
            
            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está ' . $user->status,
            ]);
        }

        // Actualizar último login
        $user->update(['last_login_at' => now()]);

        // Bitácora
        if (class_exists('\App\Models\Bitacora')) {
            \App\Models\Bitacora::registrar(
                'Autenticación',
                'LOGIN',
                'Usuario inició sesión correctamente',
                null,
                [
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'persona_id' => $user->persona_id,
                    'ip' => $request->ip()
                ]
            );
        }

        // Respuesta para AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect' => route('dashboard'),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'role' => $user->role->name ?? null,
                ]
            ]);
        }

        // Respuesta para POST tradicional
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        // Bitácora antes de cerrar sesión
        if (Auth::check() && class_exists('\App\Models\Bitacora')) {
            \App\Models\Bitacora::registrar(
                'Autenticación',
                'LOGOUT',
                'Usuario cerró sesión',
                null,
                [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip()
                ]
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Sesión cerrada']);
        }
        
        return redirect('/login');
    }
}