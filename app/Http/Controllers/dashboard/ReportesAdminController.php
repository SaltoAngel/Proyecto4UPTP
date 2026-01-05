<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportesAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reporte::query();

        // Filtros
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $reportes = $query->paginate(15);
        $categorias = Reporte::getCategorias();

        return view('dashboard.reportes.index', compact('reportes', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Reporte::getCategorias();
        return view('dashboard.reportes.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'template' => 'required|string|max:255|unique:reportes',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:100',
            'parametros' => 'nullable|json',
            'activo' => 'boolean',
            'requiere_db' => 'boolean',
            'jrxml_file' => 'nullable|file|mimes:jrxml,xml'
        ]);

        // Si se subió un archivo JRXML, guardarlo
        if ($request->hasFile('jrxml_file')) {
            $fileName = $validated['template'] . '.jrxml';
            $request->file('jrxml_file')->move(app_path('Reports/templates'), $fileName);
        }

        $reporte = Reporte::create($validated);

        Bitacora::registrar(
            'reportes',
            'crear',
            'Creó reporte ID ' . $reporte->id,
            null,
            $reporte->toArray()
        );

        return redirect()->route('dashboard.reportes.index')
            ->with('success', 'Reporte creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reporte $reporte)
    {
        return view('dashboard.reportes.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reporte $reporte)
    {
        $categorias = Reporte::getCategorias();
        return view('dashboard.reportes.edit', compact('reporte', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporte $reporte)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'template' => 'required|string|max:255|unique:reportes,template,' . $reporte->id,
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:100',
            'parametros' => 'nullable|json',
            'activo' => 'boolean',
            'requiere_db' => 'boolean',
            'jrxml_file' => 'nullable|file|mimes:jrxml,xml'
        ]);

        $datosAnteriores = $reporte->toArray();

        // Si se cambió el nombre del template y se subió un nuevo archivo
        if ($request->hasFile('jrxml_file') && $validated['template'] !== $reporte->template) {
            // Eliminar archivo anterior si existe
            $oldFile = app_path("Reports/templates/{$reporte->template}.jrxml");
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }

            // Guardar nuevo archivo
            $fileName = $validated['template'] . '.jrxml';
            $request->file('jrxml_file')->move(app_path('Reports/templates'), $fileName);
        }

        $reporte->update($validated);

        Bitacora::registrar(
            'reportes',
            'actualizar',
            'Actualizó reporte ID ' . $reporte->id,
            $datosAnteriores,
            $reporte->toArray()
        );

        return redirect()->route('dashboard.reportes.index')
            ->with('success', 'Reporte actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporte $reporte)
    {
        $datosAnteriores = $reporte->toArray();

        // Eliminar archivo JRXML si existe
        $filePath = $reporte->getTemplatePath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $reporte->delete();

        Bitacora::registrar(
            'reportes',
            'eliminar',
            'Eliminó reporte ID ' . $reporte->id,
            $datosAnteriores,
            null
        );

        return redirect()->route('dashboard.reportes.index')
            ->with('success', 'Reporte eliminado exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggle(Reporte $reporte)
    {
        $datosAnteriores = $reporte->toArray();
        $reporte->update(['activo' => !$reporte->activo]);

        Bitacora::registrar(
            'reportes',
            'actualizar_estado',
            'Actualizó estado de reporte ID ' . $reporte->id,
            $datosAnteriores,
            $reporte->toArray()
        );

        return redirect()->back()
            ->with('success', 'Estado del reporte actualizado.');
    }
}
