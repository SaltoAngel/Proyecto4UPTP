<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class supervisorController extends Controller
{
    public function dashboard()
    {
        return view('Supervisor.dashboard');
    }
}

