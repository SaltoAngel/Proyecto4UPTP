<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;    
use App\Models\User;
// use App\Notifications\TwoFactorCode; // 2FA deshabilitado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    $user = Auth::user();
    
    // ===== AUTENTICACIÓN DE DOBLE PASO DESHABILITADA =====
    // Incrementar contador de inicios de sesión
    // $user->incrementLoginCount();

    // Verificar si se requiere 2FA para este inicio de sesión
    // Usar probabilidad de 1 en 4 (25%) y mínimo 2 intentos entre verificaciones
    // if ($user->shouldRequire2FA(4, 2)) {
    //     // Generar y enviar código 2FA
    //     $code = $user->generateVerificationCode();
    //     
    //     try {
    //         $user->notifyNow(new \App\Notifications\TwoFactorCode($code, $user->login_count));
    //         
    //         // Guardar información en sesión para 2FA
    //         $request->session()->put('2fa_user_id', $user->id);
    //         $request->session()->put('2fa_remember', $request->boolean('remember'));
    //         $request->session()->put('2fa_login_count', $user->login_count);
    //
    //         Auth::logout();
    //         $request->session()->regenerate();
    //
    //         return redirect()->route('2fa.verify');
    //     } catch (\Exception $e) {
    //         \Log::error('Error enviando código 2FA: ' . $e->getMessage());
    //         
    //         Auth::logout();
    //         throw ValidationException::withMessages([
    //             'email' => 'Error enviando código de verificación. Intenta nuevamente.',
    //         ]);
    //     }
    // }
    // ===== FIN AUTENTICACIÓN DE DOBLE PASO =====

    // Iniciar sesión directamente
    \Log::info('Login directo para usuario', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);

    return redirect()->intended('/dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}