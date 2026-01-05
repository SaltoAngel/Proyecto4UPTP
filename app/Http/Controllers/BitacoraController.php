<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Bitacora::with(['user.persona'])
            ->orderBy('created_at', 'desc');
        
        // Filtros
        if ($request->filled('modulo')) {
            $query->where('modulo', 'like', '%' . $request->modulo . '%');
        }
        
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('usuario')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->usuario . '%');
            });
        }
        
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        
        $bitacora = $query->paginate(20)->appends($request->query());

        $modulos = Bitacora::select('modulo')->distinct()->orderBy('modulo')->pluck('modulo');
        $acciones = Bitacora::select('accion')->distinct()->orderBy('accion')->pluck('accion');
        
        return view('dashboard.bitacora.index', compact('bitacora', 'modulos', 'acciones'));
    }
    
    public function show(Bitacora $bitacora)
    {
        return view('dashboard.bitacora.show', compact('bitacora'));
    }
}
