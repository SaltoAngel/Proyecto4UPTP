<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PersonasRequest;
use App\Models\Personas;

class PersonasController extends Controller
{
    public function index()
    {
        $personas = Personas::with('user')->get();
        return view('dashboard.personas.index', compact('personas'));
    }
}
