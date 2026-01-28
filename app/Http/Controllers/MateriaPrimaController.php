<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\CategoriasMateriasPrimas;
use App\Models\InventarioMateria;
use App\Models\MovimientoInventario;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriaPrimaController extends Controller
{
    public function index(Request $request)
    {
        $query = MateriaPrima::with(['categoria', 'inventario'])
            ->activas()
            ->orderBy('descripcion');
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%")
                  ->orWhere('nombre_comercial', 'like', "%$search%");
            });
        }
        
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        if ($request->filled('estado_inventario')) {
            $query->whereHas('inventario', function($q) use ($request) {
                $q->where('estado', $request->estado_inventario);
            });
        }
        
        if ($request->filled('activo')) {
            $query->where('activo', true);
        }
        
        if ($request->filled('disponible')) {
            $query->where('disponible', true);
        }
        
        // Calcular estadísticas
        $totalMaterias = MateriaPrima::activas()->count();
        $criticos = MateriaPrima::whereHas('inventario', function($q) {
            $q->where('estado', 'critico');
        })->activas()->count();
        
        $agotados = MateriaPrima::whereHas('inventario', function($q) {
            $q->where('estado', 'agotado');
        })->activas()->count();
        
        // Obtener lista para select en entradas
        $materiasPrimasList = MateriaPrima::activas()->disponibles()
            ->select('id', 'codigo', 'descripcion')
            ->orderBy('descripcion')
            ->get()
            ->map(function($materia) {
                // Agregamos stock_actual como un atributo calculado
                $materia->stock_actual = $materia->stock_actual;
                return $materia;
            });
        
        // Obtener proveedores para entradas
        $proveedores = Proveedor::with(['persona' => function ($query) {
                $query->orderBy('nombres');
            }])
            ->where('estado', 'activo')
            ->get();
        
        // PAGINACIÓN: Siempre devolver paginado para la pestaña "Todos"
        $materiasPrimas = $query->paginate(31);
        $categorias = CategoriasMateriasPrimas::orderBy('nombre')->get();
        
        // Para las pestañas de estado, filtramos desde la colección paginada
        // Esto evita hacer consultas adicionales a la base de datos
        $materiasPrimasCollection = $query->get(); // Sin paginación para filtros
        
        return view('materias-primas.index', compact(
            'materiasPrimas',           // Paginado para "Todos"
            'materiasPrimasCollection', // Colección completa para filtros
            'categorias',
            'totalMaterias',
            'criticos',
            'agotados',
            'materiasPrimasList',
            'proveedores'
        ));
    }

    public function create()
    {
        // Este método no se usa directamente porque usamos modal
        return redirect()->route('materias-primas.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:materias_primas,codigo|max:50',
            'descripcion' => 'required|max:150',
            'categoria_id' => 'nullable|exists:categorias_materia_prima,id',
            'nombre_comercial' => 'nullable|max:150',
            'nombre_cientifico' => 'nullable|max:150',
            'comentario' => 'nullable',
            'activo' => 'boolean',
            'disponible' => 'boolean',
            'preferido' => 'boolean',
            'stock_minimo' => 'required|numeric|min:0',  // CAMBIADO: de inventario.stock_minimo a stock_minimo
            'stock_maximo' => 'required|numeric|gt:stock_minimo',  // CAMBIADO: de inventario.stock_maximo a stock_maximo
            'punto_reorden' => 'required|numeric|between:stock_minimo,stock_maximo',  // CAMBIADO
            'almacen' => 'nullable|max:100'  // CAMBIADO: de inventario.almacen a almacen
        ]);
        
        DB::beginTransaction();
        try {
            // Crear materia prima
            $materiaPrima = MateriaPrima::create([
                'categoria_id' => $validated['categoria_id'] ?? null,
                'descripcion' => $validated['descripcion'],
                'codigo' => $validated['codigo'],
                'nombre_comercial' => $validated['nombre_comercial'] ?? null,
                'nombre_cientifico' => $validated['nombre_cientifico'] ?? null,
                'comentario' => $validated['comentario'] ?? null,
                'activo' => $validated['activo'] ?? true,
                'disponible' => $validated['disponible'] ?? true,
                'preferido' => $validated['preferido'] ?? false,
                'fecha_creacion' => now(),
                'fecha_modificacion' => now()
            ]);
            
            // Crear inventario
            $inventario = InventarioMateria::create([
                'materia_prima_id' => $materiaPrima->id,
                'stock_actual' => 0,
                'stock_minimo' => $validated['stock_minimo'],  // CAMBIADO
                'stock_maximo' => $validated['stock_maximo'],  // CAMBIADO
                'punto_reorden' => $validated['punto_reorden'],  // CAMBIADO
                'almacen' => $validated['almacen'] ?? null,  // CAMBIADO
                'estado' => 'normal',
                'fecha_ultima_verificacion' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Materia prima creada exitosamente',
                'data' => $materiaPrima
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear materia prima: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $materia = MateriaPrima::with([
            'categoria',
            'inventario',
            'movimientos' => function($query) {
                $query->with('proveedor')
                      ->orderBy('fecha_movimiento', 'desc')
                      ->limit(10);
            }
        ])->findOrFail($id);
        
        return view('materias-primas.show', compact('materia'));
    }

    public function edit($id)
    {
        $materia = MateriaPrima::with('inventario')->findOrFail($id);
        $categorias = CategoriasMateriasPrimas::orderBy('nombre')->get();
        
        return view('materias-primas.edit', compact('materia', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $materia = MateriaPrima::findOrFail($id);
        
        $validated = $request->validate([
            'codigo' => 'required|unique:materias_primas,codigo,' . $id . '|max:50',
            'descripcion' => 'required|max:150',
            'categoria_id' => 'nullable|exists:categorias_materia_prima,id',
            'nombre_comercial' => 'nullable|max:150',
            'nombre_cientifico' => 'nullable|max:150',
            'comentario' => 'nullable',
            'activo' => 'boolean',
            'disponible' => 'boolean',
            'preferido' => 'boolean',
            'stock_minimo' => 'required|numeric|min:0',  // CAMBIADO: de inventario.stock_minimo a stock_minimo
            'stock_maximo' => 'required|numeric|gt:stock_minimo',  // CAMBIADO: de inventario.stock_maximo a stock_maximo
            'punto_reorden' => 'required|numeric|between:stock_minimo,stock_maximo',  // CAMBIADO
            'almacen' => 'nullable|max:100'  // CAMBIADO: de inventario.almacen a almacen
        ]);
        
        DB::beginTransaction();
        try {
            // Actualizar materia prima
            $materia->update([
                'categoria_id' => $validated['categoria_id'] ?? null,
                'descripcion' => $validated['descripcion'],
                'codigo' => $validated['codigo'],
                'nombre_comercial' => $validated['nombre_comercial'] ?? null,
                'nombre_cientifico' => $validated['nombre_cientifico'] ?? null,
                'comentario' => $validated['comentario'] ?? null,
                'activo' => $validated['activo'] ?? true,
                'disponible' => $validated['disponible'] ?? true,
                'preferido' => $validated['preferido'] ?? false,
                'fecha_modificacion' => now()
            ]);
            
            // Actualizar o crear inventario
            if ($materia->inventario) {
                $materia->inventario->update([
                    'stock_minimo' => $validated['stock_minimo'],  // CAMBIADO
                    'stock_maximo' => $validated['stock_maximo'],  // CAMBIADO
                    'punto_reorden' => $validated['punto_reorden'],  // CAMBIADO
                    'almacen' => $validated['almacen'] ?? null,  // CAMBIADO
                    'fecha_ultima_verificacion' => now()
                ]);
                
                // Recalcular estado
                $materia->inventario->actualizarEstado();
            } else {
                InventarioMateria::create([
                    'materia_prima_id' => $materia->id,
                    'stock_actual' => 0,
                    'stock_minimo' => $validated['stock_minimo'],  // CAMBIADO
                    'stock_maximo' => $validated['stock_maximo'],  // CAMBIADO
                    'punto_reorden' => $validated['punto_reorden'],  // CAMBIADO
                    'almacen' => $validated['almacen'] ?? null,  // CAMBIADO
                    'estado' => 'normal',
                    'fecha_ultima_verificacion' => now()
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Materia prima actualizada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $materia = MateriaPrima::findOrFail($id);
            
            // Verificar si tiene movimientos
            $tieneMovimientos = $materia->movimientos()->exists();
            
            if ($tieneMovimientos) {
                // Soft delete en lugar de eliminar físicamente
                $materia->update(['activo' => false, 'disponible' => false]);
                $message = 'Materia prima desactivada (tiene movimientos registrados)';
            } else {
                // Eliminar físicamente
                if ($materia->inventario) {
                    $materia->inventario->delete();
                }
                $materia->delete();
                $message = 'Materia prima eliminada exitosamente';
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ], 500);
        }
    }
}