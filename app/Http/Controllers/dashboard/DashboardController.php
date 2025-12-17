<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personas;
use App\Models\Proveedor;
use App\Models\Bitacora;

class DashboardController extends Controller
{
    public function index()
    {
        $personasCount = Personas::count();
        $proveedoresCount = Proveedor::count();
        $bitacoraCount = class_exists(Bitacora::class) ? Bitacora::count() : null;

        // Demo de Top 5 clientes: usando personas más recientes como placeholder
        $topClientes = Personas::orderByDesc('id')
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'nombre' => $p->nombre_completo ?? '—',
                    'total' => 0,
                ];
            });

        return view('material.dashboard', compact(
            'personasCount',
            'proveedoresCount',
            'bitacoraCount',
            'topClientes'
        ));
    }
}
