<?php

namespace App\Http\Controllers\Nutricionista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class nutricionistaController extends Controller
{
    public function dashboard() {
        return view('Nutricionista.dashboard');
    }
}
