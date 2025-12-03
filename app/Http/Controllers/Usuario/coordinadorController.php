<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class coordinadorController extends Controller
{
    public function dashboard()
    {
        return view('Coordinador.dashboard');
    }
}

