<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function showVerificationForm()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('2fa_user_id'));
        
        return view('auth.2fa-verify', [
            'user' => $user,
            'login_count' => session('2fa_login_count'),
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'full_code' => 'required|string|size:6',
        ]);

        $user = User::find(session('2fa_user_id'));

        if (!$user || !$user->verifyCode($request->full_code)) {
            return back()->withErrors(['code' => 'El código de verificación es inválido o ha expirado.']);
        }

        // Iniciar sesión
        Auth::login($user, session('2fa_remember'));

        // Limpiar sesión
        session()->forget(['2fa_user_id', '2fa_remember', '2fa_login_count']);


        return redirect()->intended('/dashboard');
    }

    public function resendCode(Request $request)
    {
        $user = User::find(session('2fa_user_id'));

        if ($user) {
            $code = $user->generateVerificationCode();
            
            try {
                $user->notifyNow(new \App\Notifications\TwoFactorCode($code, $user->login_count));
                
                Log::info('Código 2FA reenviado', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'login_count' => $user->login_count,
                ]);
                
                return back()->with('status', 'Se ha enviado un nuevo código de verificación.');
            } catch (\Exception $e) {
                Log::error('Error reenviando código 2FA: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Error enviando código de verificación.']);
            }
        }

        return redirect()->route('login');
    }
}