<?php
// app/Http/Controllers/dashboard/PersonasController.php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PersonasRequest;
use App\Models\Personas as Persona;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PersonasController extends Controller
{
    /**
     * Mostrar listado de personas
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $personas = Persona::withTrashed()
            ->when($search, function ($query, $search) {
                return $query->buscar($search);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
            return view('dashboard.personas.index', compact('personas', 'search'));
            
        } catch (\Exception $e) {
            Log::error('Error en index de personas: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Error al cargar el listado de personas');
        }
    }
    
    /**
     * Almacenar nueva persona (AJAX y tradicional)
     */
    public function store(PersonasRequest $request)
    {
        try {
            $data = $request->validated();
            $data['activo'] = $data['activo'] ?? true;
            $data['codigo'] = $data['codigo'] ?? Persona::generarCodigo($data['tipo_documento']);
            
            $persona = Persona::create($data);
            $nombrePersona = $persona->tipo === 'juridica'
                ? ($persona->razon_social ?: $persona->nombre_comercial)
                : trim(($persona->nombres ?? '') . ' ' . ($persona->apellidos ?? ''));

            Bitacora::registrar(
                'personas',
                'create',
                'Creó persona: ' . ($nombrePersona ?: ('ID ' . $persona->id)),
                null,
                $persona->toArray()
            );
            
            Log::info('Persona creada exitosamente', ['id' => $persona->id]);
            
            // Si es petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Persona creada exitosamente',
                    'data' => $persona,
                    'redirect' => route('dashboard.personas.index')
                ], 201);
            }
            
            // Si es petición normal, redirigir
            return redirect()->route('dashboard.personas.index')
                ->with('success', 'Persona creada exitosamente');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Para AJAX, devolver errores en JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Error al crear persona: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la persona: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Error al crear la persona');
        }
    }
    
    
    /**
     * Actualizar persona existente
     */
    public function update(PersonasRequest $request, Persona $persona)
    {
        try {
            Log::info('Actualizando persona', ['id' => $persona->id, 'data' => $request->except('_token', '_method')]);
            $datosAnteriores = $persona->toArray();
            
            $persona->update($request->validated());
            $nombrePersona = $persona->tipo === 'juridica'
                ? ($persona->razon_social ?: $persona->nombre_comercial)
                : trim(($persona->nombres ?? '') . ' ' . ($persona->apellidos ?? ''));

            Bitacora::registrar(
                'personas',
                'update',
                'Actualizó persona: ' . ($nombrePersona ?: ('ID ' . $persona->id)),
                $datosAnteriores,
                $persona->toArray()
            );
            
            Log::info('Persona actualizada exitosamente', ['id' => $persona->id]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Persona actualizada exitosamente',
                    'data' => $persona
                ]);
            }
            
            return redirect()->route('dashboard.personas.index')
                ->with('success', 'Persona actualizada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar persona: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la persona'
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Error al actualizar la persona');
        }
    }
    
    /**
     * Deshabilitar persona (soft delete + flag)
     */
    public function destroy(Request $request, Persona $persona)
    {
        try {
            Log::warning('Deshabilitando persona', ['id' => $persona->id]);
            $datosAnteriores = $persona->toArray();
            $persona->activo = false;
            $persona->save();
            $persona->delete();
            $nombrePersona = $persona->tipo === 'juridica'
                ? ($persona->razon_social ?: $persona->nombre_comercial)
                : trim(($persona->nombres ?? '') . ' ' . ($persona->apellidos ?? ''));

            Bitacora::registrar(
                'personas',
                'deshabilitar',
                'Deshabilitó persona: ' . ($nombrePersona ?: ('ID ' . $persona->id)),
                $datosAnteriores,
                $persona->toArray()
            );
            
            Log::warning('Persona deshabilitada exitosamente', ['id' => $persona->id]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Persona deshabilitada exitosamente'
                ]);
            }
            
            return redirect()->route('dashboard.personas.index')
                ->with('success', 'Persona deshabilitada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar persona: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la persona'
                ], 500);
            }
            
            return back()->with('error', 'Error al eliminar la persona');
        }
    }
    
    /**
     * Restaurar persona deshabilitada
     */
    public function restore(Request $request, $id)
    {
        try {
            $persona = Persona::withTrashed()->findOrFail($id);
            $datosAnteriores = $persona->toArray();
            $persona->restore();
            $persona->activo = true;
            $persona->save();
            $nombrePersona = $persona->tipo === 'juridica'
                ? ($persona->razon_social ?: $persona->nombre_comercial)
                : trim(($persona->nombres ?? '') . ' ' . ($persona->apellidos ?? ''));

            Bitacora::registrar(
                'personas',
                'restaurar',
                'Restauró persona: ' . ($nombrePersona ?: ('ID ' . $persona->id)),
                $datosAnteriores,
                $persona->toArray()
            );
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Persona restaurada exitosamente',
                    'data' => $persona
                ]);
            }

            return redirect()->route('dashboard.personas.index')
                ->with('success', 'Persona restaurada exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al restaurar persona: ' . $e->getMessage());
            return back()->with('error', 'Error al restaurar la persona');
        }
    }
    
    /**
     * Buscar personas (para autocomplete)
     */
    public function buscar(Request $request)
    {
        try {
            $search = $request->get('q');
            
            $personas = Persona::when($search, function ($query, $search) {
                return $query->where('documento', 'like', "%{$search}%")
                    ->orWhere('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('razon_social', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'codigo', 'nombres', 'apellidos', 'razon_social', 'documento', 'tipo_documento', 'email']);
            
            $formatted = $personas->map(function ($persona) {
                return [
                    'id' => $persona->id,
                    'text' => $persona->nombre_completo . ' (' . $persona->documento_completo . ')',
                    'documento' => $persona->documento,
                    'email' => $persona->email,
                ];
            });
            
            return response()->json($formatted);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de personas: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}