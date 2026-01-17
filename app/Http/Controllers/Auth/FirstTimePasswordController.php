// app/Http/Controllers/Auth/FirstTimePasswordController.php
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
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $user = Auth::user();

        // Verificar que la contraseña actual sea la cédula
        $documento = str_replace(['.', '-'], '', $user->persona->documento);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.'
            ]);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->password),
            'status' => 'activo',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada exitosamente. Bienvenido al sistema!');
    }
}