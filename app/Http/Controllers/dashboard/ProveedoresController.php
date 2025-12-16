<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProveedoresRequest;
use App\Models\Bitacora;
use App\Models\Personas;
use App\Models\Proveedor;
use App\Models\TiposProveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProveedoresController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');

            $proveedores = Proveedor::with([
                    'persona' => fn ($q) => $q->withTrashed(),
                    'tiposProveedores'
                ])
                ->withTrashed()
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('codigo_proveedor', 'like', "%{$search}%")
                            ->orWhere('categoria', 'like', "%{$search}%")
                            ->orWhere('contacto_comercial', 'like', "%{$search}%")
                            ->orWhere('email_comercial', 'like', "%{$search}%")
                            ->orWhereHas('persona', function ($personaQuery) use ($search) {
                                $personaQuery->where('codigo', 'like', "%{$search}%")
                                    ->orWhere('documento', 'like', "%{$search}%")
                                    ->orWhere('nombres', 'like', "%{$search}%")
                                    ->orWhere('apellidos', 'like', "%{$search}%")
                                    ->orWhere('razon_social', 'like', "%{$search}%");
                            });
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $personas = Personas::orderBy('codigo')->get();
            $tiposProveedores = TiposProveedores::orderBy('nombre_tipo')->get();

            return view('dashboard.proveedores.index', compact('proveedores', 'personas', 'tiposProveedores', 'search'));
        } catch (\Exception $e) {
            Log::error('Error en index de proveedores: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Error al cargar el listado de proveedores');
        }
    }

    public function store(ProveedoresRequest $request)
    {
        try {
            $data = $request->validated();
            $tipos = $data['tipos_proveedores'] ?? [];
            unset($data['tipos_proveedores']);
            $data['codigo_proveedor'] = $data['codigo_proveedor'] ?? Proveedor::generarCodigo();
            $data['fecha_registro'] = $data['fecha_registro'] ?? now()->toDateString();
            $data['estado'] = $data['estado'] ?? 'activo';

            $proveedor = Proveedor::create($data);
            if (!empty($tipos)) {
                $proveedor->tiposProveedores()->sync($tipos);
            }

            Bitacora::registrar(
                'proveedores',
                'crear',
                'Creó proveedor ID ' . $proveedor->id,
                null,
                $proveedor->toArray()
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor creado exitosamente',
                    'data' => $proveedor,
                    'redirect' => route('dashboard.proveedores.index')
                ], 201);
            }

            return redirect()->route('dashboard.proveedores.index')
                ->with('success', 'Proveedor creado exitosamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        } catch (\Exception $e) {
            Log::error('Error al crear proveedor: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el proveedor: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al crear el proveedor');
        }
    }

    public function update(ProveedoresRequest $request, Proveedor $proveedor)
    {
        try {
            $datosAnteriores = $proveedor->toArray();
            $data = $request->validated();
            $tipos = $data['tipos_proveedores'] ?? [];
            unset($data['tipos_proveedores']);
            $data['codigo_proveedor'] = $data['codigo_proveedor'] ?? $proveedor->codigo_proveedor ?? Proveedor::generarCodigo();

            $proveedor->update($data);
            $proveedor->tiposProveedores()->sync($tipos);

            Bitacora::registrar(
                'proveedores',
                'actualizar',
                'Actualizó proveedor ID ' . $proveedor->id,
                $datosAnteriores,
                $proveedor->toArray()
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor actualizado exitosamente',
                    'data' => $proveedor
                ]);
            }

            return redirect()->route('dashboard.proveedores.index')
                ->with('success', 'Proveedor actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar proveedor: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el proveedor'
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error al actualizar el proveedor');
        }
    }

    public function destroy(Request $request, Proveedor $proveedor)
    {
        try {
            $datosAnteriores = $proveedor->toArray();
            $proveedor->estado = 'inactivo';
            $proveedor->save();
            $proveedor->delete();

            Bitacora::registrar(
                'proveedores',
                'deshabilitar',
                'Deshabilitó proveedor ID ' . $proveedor->id,
                $datosAnteriores,
                $proveedor->toArray()
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor deshabilitado exitosamente'
                ]);
            }

            return redirect()->route('dashboard.proveedores.index')
                ->with('success', 'Proveedor deshabilitado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar proveedor: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el proveedor'
                ], 500);
            }

            return back()->with('error', 'Error al eliminar el proveedor');
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            $proveedor = Proveedor::withTrashed()->findOrFail($id);
            $datosAnteriores = $proveedor->toArray();
            $proveedor->restore();
            $proveedor->estado = 'activo';
            $proveedor->save();

            Bitacora::registrar(
                'proveedores',
                'restaurar',
                'Restauró proveedor ID ' . $proveedor->id,
                $datosAnteriores,
                $proveedor->toArray()
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor restaurado exitosamente',
                    'data' => $proveedor
                ]);
            }

            return redirect()->route('dashboard.proveedores.index')
                ->with('success', 'Proveedor restaurado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al restaurar proveedor: ' . $e->getMessage());
            return back()->with('error', 'Error al restaurar el proveedor');
        }
    }

    public function buscar(Request $request)
    {
        try {
            $search = $request->get('q');

            $proveedores = Proveedor::with(['persona'])
                ->when($search, function ($query, $search) {
                    $query->where('codigo_proveedor', 'like', "%{$search}%")
                        ->orWhereHas('persona', function ($personaQuery) use ($search) {
                            $personaQuery->where('codigo', 'like', "%{$search}%")
                                ->orWhere('nombres', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%")
                                ->orWhere('razon_social', 'like', "%{$search}%")
                                ->orWhere('documento', 'like', "%{$search}%");
                        });
                })
                ->limit(10)
                ->get();

            $formatted = $proveedores->map(function ($proveedor) {
                $persona = $proveedor->persona;
                $nombre = $persona?->nombre_completo ?? 'Sin persona';
                return [
                    'id' => $proveedor->id,
                    'text' => $proveedor->codigo_proveedor . ' - ' . $nombre,
                    'persona' => $persona,
                ];
            });

            return response()->json($formatted);
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de proveedores: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}
