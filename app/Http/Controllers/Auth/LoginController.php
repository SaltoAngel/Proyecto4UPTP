<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /*Declaración de redireccion mediante credenciales de usuario */
    protected function authenticated (request $request, $user) {
        
        if ($user->hasRoles('ADMINISTRADOR')) {
            return redirect('admin/dashboard');
        } elseif($user->hasRoles('NUTRICIONISTA')) {
            return redirect('nutricionista/dashboard');
        } elseif($user->hasRoles('SUPERVISOR')) {
            return redirect('supervisor/dashboard');
        } elseif($user->hasRoles('COORDINADOR')) {
            return redirect('coordinador/dashboard');
        }
        return redirect('/');
    }
}
