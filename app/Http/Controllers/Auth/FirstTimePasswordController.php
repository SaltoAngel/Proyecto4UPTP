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
        if (Auth::check() && Auth::user()->status !== 'pendiente') {
            return redirect()->route('dashboard');
        }

        return view('auth.first_time_password');
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

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada exitosamente. ¡Bienvenido al sistema!');
    }
}