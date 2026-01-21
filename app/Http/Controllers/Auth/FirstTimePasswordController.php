<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FirstTimePasswordController extends Controller
{
    public function showPasswordForm()
    {
        // Solo usuarios autenticados pueden ver este formulario
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Si el usuario ya no está pendiente, redirigir al dashboard
        if ($user->status !== 'pendiente') {
            return redirect()->route('dashboard');
        }

        // Usar un layout diferente (no el del dashboard)
        return view('auth.first_time_password')->with('layout', 'auth');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $user = Auth::user();

        // Verificar que el usuario esté en estado pendiente
        if ($user->status !== 'pendiente') {
            return redirect()->route('dashboard')->with('info', 'Ya ha cambiado su contraseña anteriormente.');
        }

        // Verificar la contraseña ACTUAL (la que se estableció al crear el usuario)
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.'
            ])->withInput();
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->password),
            'status' => 'activo',
        ]);

        // Cerrar sesión para que inicie con la nueva contraseña
        Auth::logout();

        // Redirigir al login con mensaje de éxito
        return redirect()->route('login')
            ->with('success', '¡Contraseña actualizada exitosamente! Ahora puede iniciar sesión con su nueva contraseña.');
    }
}