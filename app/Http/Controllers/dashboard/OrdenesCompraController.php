<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controlador: OrdenesCompraController
 * Propósito: Listar y crear órdenes de compra (stubs).
 */
class OrdenesCompraController extends Controller
{
    public function index()
    {
        return view('material.ordenes.index');
    }

    public function create()
    {
        return view('material.ordenes.create');
    }
}
