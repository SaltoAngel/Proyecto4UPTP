<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    protected $messages = [
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser válido.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        // Debug: always dispatch at start
        $this->dispatch('show-sweetalert', [
            'type' => 'info',
            'title' => 'Debug',
            'text' => 'Método login ejecutado',
        ]);

        $this->validate();

        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->dispatch('show-sweetalert', [
                'type' => 'error',
                'title' => 'Demasiados intentos',
                'text' => "Demasiados intentos de inicio de sesión. Inténtalo de nuevo en {$seconds} segundos.",
            ]);
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey);

            $this->dispatch('show-sweetalert', [
                'type' => 'error',
                'title' => 'Credenciales incorrectas',
                'text' => 'El correo electrónico o la contraseña son incorrectos.',
            ]);
            return;
        }

        RateLimiter::clear($throttleKey);

        session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}