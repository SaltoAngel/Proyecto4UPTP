<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controlador: RecepcionesController
 * Propósito: Listar y crear recepciones (stubs).
 */
class RecepcionesController extends Controller
{
    public function index()
    {
        return view('material.recepciones.index');
    }

    public function create()
    {
        return view('material.recepciones.create');
    }
}
