<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimalRequest;
use App\Models\Aminoacido;
use App\Models\Especie;
use App\Models\RequerimientoNutricional;
use App\Models\TipoAnimal;
use App\Models\Mineral;
use App\Models\Vitamina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalesController extends Controller
{
    public function index()
    {
        $especies = Especie::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']);
        $aminoacidos = Aminoacido::orderBy('orden')->orderBy('nombre')->get(['id', 'nombre', 'abreviatura', 'esencial']);
        $minerales = Mineral::orderBy('orden')->orderBy('nombre')->get(['id', 'nombre', 'unidad', 'simbolo', 'esencial']);
        $vitaminas = Vitamina::orderBy('orden')->orderBy('nombre')->get(['id', 'nombre', 'unidad', 'tipo', 'esencial']);

        // DataTables paginará en frontend, traemos todos los registros
        $tipos = TipoAnimal::with('especie')
            ->orderByDesc('id')
            ->get();

        return view('dashboard.animales.index', compact('especies', 'aminoacidos', 'minerales', 'vitaminas', 'tipos'));
    }

    public function store(StoreAnimalRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $tipo = TipoAnimal::create([
                'especie_id' => $validated['especie_id'],
                'nombre' => $validated['nombre'],
                'codigo_etapa' => $validated['codigo_etapa'] ?? null,
                'edad_minima_dias' => $validated['edad_minima_dias'] ?? null,
                'edad_maxima_dias' => $validated['edad_maxima_dias'] ?? null,
                'peso_minimo_kg' => $validated['peso_minimo_kg'] ?? null,
                'peso_maximo_kg' => $validated['peso_maximo_kg'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $validated['activo'] ?? true,
            ]);

            $reqData = $validated['requerimiento'] ?? [];
            $requerimiento = RequerimientoNutricional::create([
                'tipo_animal_id' => $tipo->id,
                'descripcion' => $reqData['descripcion'] ?? 'Base',
                'fuente' => $reqData['fuente'] ?? null,
                'comentario' => $reqData['comentario'] ?? null,
                'consumo_esperado_kg_dia' => $reqData['consumo_esperado_kg_dia'] ?? null,
                'preferido' => $reqData['preferido'] ?? false,
                'humedad' => $reqData['humedad'] ?? null,
                'materia_seca' => $reqData['materia_seca'] ?? null,
                'proteina_cruda' => $reqData['proteina_cruda'] ?? null,
                'fibra_bruta' => $reqData['fibra_bruta'] ?? null,
                'extracto_etereo' => $reqData['extracto_etereo'] ?? null,
                'eln' => $reqData['eln'] ?? null,
                'ceniza' => $reqData['ceniza'] ?? null,
                'energia_digestible' => $reqData['energia_digestible'] ?? null,
                'energia_metabolizable' => $reqData['energia_metabolizable'] ?? null,
                'energia_neta' => $reqData['energia_neta'] ?? null,
                'energia_digestible_ap' => $reqData['energia_digestible_ap'] ?? null,
                'energia_metabolizable_ap' => $reqData['energia_metabolizable_ap'] ?? null,
                'activo' => $reqData['activo'] ?? true,
            ]);

            $pivotData = [];
            foreach (($validated['aminoacidos'] ?? []) as $aminoId => $vals) {
                $pivotData[$aminoId] = [
                    'valor_min' => $vals['valor_min'] ?? null,
                    'valor_max' => $vals['valor_max'] ?? null,
                    'valor_recomendado' => $vals['valor_recomendado'] ?? null,
                    'unidad' => $vals['unidad'] ?? '%',
                ];
            }
            if (!empty($pivotData)) {
                $requerimiento->aminoacidos()->sync($pivotData);
            }

            $mineralesPivot = [];
            foreach (($validated['minerales'] ?? []) as $mineralId => $vals) {
                $mineralesPivot[$mineralId] = [
                    'valor_min' => $vals['valor_min'] ?? null,
                    'valor_max' => $vals['valor_max'] ?? null,
                    'valor_recomendado' => $vals['valor_recomendado'] ?? null,
                    'unidad' => $vals['unidad'] ?? 'mg/kg',
                ];
            }
            if (!empty($mineralesPivot)) {
                $requerimiento->minerales()->sync($mineralesPivot);
            }

            $vitaminasPivot = [];
            foreach (($validated['vitaminas'] ?? []) as $vitaminaId => $vals) {
                $vitaminasPivot[$vitaminaId] = [
                    'valor_min' => $vals['valor_min'] ?? null,
                    'valor_max' => $vals['valor_max'] ?? null,
                    'valor_recomendado' => $vals['valor_recomendado'] ?? null,
                    'unidad' => $vals['unidad'] ?? 'UI/kg',
                ];
            }
            if (!empty($vitaminasPivot)) {
                $requerimiento->vitaminas()->sync($vitaminasPivot);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Animal registrado correctamente',
                    'data' => $tipo->load('especie', 'requerimientos')
                ]);
            }

            return redirect()->route('dashboard.animales.index')->with('status', 'Animal registrado correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No se pudo registrar el animal', 'error' => $e->getMessage()], 500);
            }
            return back()->withErrors('No se pudo registrar el animal: ' . $e->getMessage())->withInput();
        }
    }
}
