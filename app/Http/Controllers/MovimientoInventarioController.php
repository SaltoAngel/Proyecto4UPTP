<?php
// app/Http/Controllers/MovimientoInventarioController.php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\MateriaPrima;
use App\Models\InventarioMateria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MovimientoInventarioController extends Controller
{
    /**
     * Método index para listar todos los movimientos
     */
    public function index()
    {
        $movimientos = MovimientoInventario::with(['materiaPrima', 'proveedor', 'usuario'])
            ->orderBy('fecha_recepcion', 'desc')
            ->paginate(20);
            
        return view('movimientos.index', compact('movimientos'));
    }
    
    /**
     * Método create para mostrar el formulario de creación
     */
    public function create()
    {
        $materiasPrimas = MateriaPrima::all();
        $proveedores = Proveedor::all();
        return view('movimientos.create', compact('materiasPrimas', 'proveedores'));
    }
    
    /**
     * Método store general para manejar ambos tipos de movimientos
     */
    public function store(Request $request)
    {
        // Redirigir a los métodos específicos según el tipo
        if ($request->has('tipo_movimiento')) {
            if ($request->tipo_movimiento === 'entrada') {
                return $this->storeEntrada($request);
            } elseif ($request->tipo_movimiento === 'salida') {
                return $this->storeSalida($request);
            }
        }
        
        // Validación para el método store unificado
        $validated = $request->validate([
            'tipo_movimiento' => 'required|in:entrada,salida',
            'materia_prima_id' => 'required|exists:materias_primas,id',
            // Campos específicos para entrada
            'proveedor_id' => 'nullable|required_if:tipo_movimiento,entrada|exists:proveedores,id',
            'costo_unitario' => 'nullable|required_if:tipo_movimiento,entrada|numeric|min:0',
            'costo_total' => 'nullable|required_if:tipo_movimiento,entrada|numeric|min:0',
            'numero_factura' => 'nullable|string|max:50',
            'numero_remision' => 'nullable|string|max:50',
            'lote' => 'nullable|string|max:100',
            'fecha_factura' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_recepcion',
            // Campos específicos para salida
            'destino' => 'nullable|required_if:tipo_movimiento,salida|string|max:200',
            // Campos comunes
            'cantidad' => 'required|numeric|min:0.01',
            'numero_documento' => 'nullable|string|max:100',
            'fecha_recepcion' => 'required|date',
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            if ($validated['tipo_movimiento'] === 'entrada') {
                return $this->storeEntrada(new Request($validated));
            } else {
                return $this->storeSalida(new Request($validated));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar movimiento: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Método show para mostrar un movimiento específico
     */
    public function show($id)
    {
        $movimiento = MovimientoInventario::with(['materiaPrima', 'proveedor', 'usuario'])->findOrFail($id);
        return view('movimientos.show', compact('movimiento'));
    }
    
    /**
     * Método edit para mostrar el formulario de edición
     */
    public function edit($id)
    {
        $movimiento = MovimientoInventario::findOrFail($id);
        $materiasPrimas = MateriaPrima::all();
        $proveedores = Proveedor::all();
        return view('movimientos.edit', compact('movimiento', 'materiasPrimas', 'proveedores'));
    }
    
    /**
     * Método update para actualizar un movimiento
     */
    public function update(Request $request, $id)
    {
        $movimiento = MovimientoInventario::findOrFail($id);
        
        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:0.01',
            'numero_documento' => 'nullable|string|max:100',
            'fecha_recepcion' => 'required|date',
            'observaciones' => 'nullable|string|max:500'
        ]);
        
        DB::beginTransaction();
        try {
            // Actualizar movimiento
            $movimiento->update($validated);
            
            // Si es una entrada, también actualizar campos específicos
            if ($movimiento->tipo_movimiento === 'entrada') {
                $entradaData = $request->validate([
                    'proveedor_id' => 'nullable|exists:proveedores,id',
                    'costo_unitario' => 'nullable|numeric|min:0',
                    'costo_total' => 'nullable|numeric|min:0',
                    'numero_factura' => 'nullable|string|max:50',
                    'numero_remision' => 'nullable|string|max:50',
                    'lote' => 'nullable|string|max:100',
                    'fecha_factura' => 'nullable|date',
                    'fecha_vencimiento' => 'nullable|date'
                ]);
                
                $movimiento->update($entradaData);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Movimiento actualizado exitosamente',
                'data' => $movimiento
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar movimiento: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Método destroy para eliminar un movimiento
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $movimiento = MovimientoInventario::findOrFail($id);
            
            // Revertir el movimiento en el inventario
            $inventario = InventarioMateria::where('materia_prima_id', $movimiento->materia_prima_id)->first();
            
            if ($inventario) {
                if ($movimiento->tipo_movimiento === 'entrada') {
                    // Restar la cantidad si era una entrada
                    $inventario->stock_actual = max(0, $inventario->stock_actual - $movimiento->cantidad);
                } else {
                    // Sumar la cantidad si era una salida
                    $inventario->stock_actual += $movimiento->cantidad;
                }
                
                $inventario->save();
                $inventario->actualizarEstado();
            }
            
            // Eliminar el movimiento
            $movimiento->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Movimiento eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar movimiento: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Método para registrar una entrada de inventario
     */
    public function storeEntrada(Request $request)
    {
        $validated = $request->validate([
            'materia_prima_id' => 'required|exists:materias_primas,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'cantidad' => 'required|numeric|min:0.01',
            'costo_unitario' => 'required|numeric|min:0',
            'costo_total' => 'required|numeric|min:0',
            'numero_factura' => 'nullable|string|max:50',
            'numero_remision' => 'nullable|string|max:50',
            'lote' => 'nullable|string|max:100',
            'numero_documento' => 'nullable|string|max:100',
            'fecha_factura' => 'nullable|date',
            'fecha_recepcion' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_recepcion',
            'observaciones' => 'nullable|string|max:500'
        ]);
        
        DB::beginTransaction();
        try {
            // Crear movimiento
            $movimiento = MovimientoInventario::create([
                'materia_prima_id' => $validated['materia_prima_id'],
                'proveedor_id' => $validated['proveedor_id'] ?? null,
                'tipo_movimiento' => 'entrada',
                'cantidad' => $validated['cantidad'],
                'costo_unitario' => $validated['costo_unitario'],
                'costo_total' => $validated['costo_total'],
                'numero_factura' => $validated['numero_factura'] ?? null,
                'numero_remision' => $validated['numero_remision'] ?? null,
                'lote' => $validated['lote'] ?? null,
                'numero_documento' => $validated['numero_documento'] ?? null,
                'fecha_factura' => $validated['fecha_factura'] ?? null,
                'fecha_recepcion' => $validated['fecha_recepcion'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? null,
                'observaciones' => $validated['observaciones'] ?? null,
                'usuario_id' => Auth::id()
            ]);
            
            // Actualizar inventario
            $inventario = InventarioMateria::where('materia_prima_id', $validated['materia_prima_id'])->first();
            
            if ($inventario) {
                // Actualizar stock
                $nuevoStock = $inventario->stock_actual + $validated['cantidad'];
                $inventario->update([
                    'stock_actual' => $nuevoStock,
                    'fecha_ultima_verificacion' => now()
                ]);
                
                // Recalcular estado
                $inventario->actualizarEstado();
                
                // Actualizar fecha última compra en materia prima
                MateriaPrima::where('id', $validated['materia_prima_id'])
                    ->update(['fecha_ultima_compra' => now()]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Entrada registrada exitosamente',
                'data' => $movimiento
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar entrada: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Método para registrar una salida de inventario
     */
    public function storeSalida(Request $request)
    {
        $validated = $request->validate([
            'materia_prima_id' => 'required|exists:materias_primas,id',
            'cantidad' => 'required|numeric|min:0.01',
            'destino' => 'required|string|max:200',
            'numero_documento' => 'nullable|string|max:100',
            'fecha_salida' => 'required|date',
            'observaciones' => 'nullable|string|max:500'
        ]);
        
        DB::beginTransaction();
        try {
            // Verificar stock disponible
            $inventario = InventarioMateria::where('materia_prima_id', $validated['materia_prima_id'])->first();
            
            if (!$inventario || $inventario->stock_actual < $validated['cantidad']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente para realizar la salida'
                ], 400);
            }
            
            // Crear movimiento
            $movimiento = MovimientoInventario::create([
                'materia_prima_id' => $validated['materia_prima_id'],
                'tipo_movimiento' => 'salida',
                'cantidad' => $validated['cantidad'],
                'numero_documento' => $validated['numero_documento'] ?? null,
                'fecha_recepcion' => $validated['fecha_salida'],
                'observaciones' => 'Salida a: ' . $validated['destino'] . '. ' . ($validated['observaciones'] ?? ''),
                'usuario_id' => Auth::id()
            ]);
            
            // Actualizar inventario
            $nuevoStock = $inventario->stock_actual - $validated['cantidad'];
            $inventario->update([
                'stock_actual' => $nuevoStock,
                'fecha_ultima_verificacion' => now()
            ]);
            
            // Recalcular estado
            $inventario->actualizarEstado();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Salida registrada exitosamente',
                'data' => $movimiento
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar salida: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Método para obtener el historial de movimientos de una materia prima
     */
    public function getHistorial($materiaId)
    {
        $movimientos = MovimientoInventario::with(['proveedor', 'usuario'])
            ->where('materia_prima_id', $materiaId)
            ->orderBy('fecha_recepcion', 'desc')
            ->paginate(20);
            
        return response()->json([
            'success' => true,
            'data' => $movimientos
        ]);
    }
}