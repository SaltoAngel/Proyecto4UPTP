<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnimalRequest;
use App\Models\Aminoacido;
use App\Models\Especie;
use App\Models\Mineral;
use App\Models\RequerimientoNutricional;
use App\Models\TipoAnimal;
use App\Models\Vitamina;
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

        // Variables adicionales para el modal según las notas de integración
        $weendeNutrientes = [
            'humedad' => 'Humedad',
            'materia_seca' => 'Materia Seca',
            'proteina_cruda' => 'Proteína Cruda',
            'fibra_bruta' => 'Fibra Bruta',
            'extracto_etereo' => 'Extracto Etéreo',
            'eln' => 'ELN (Extracto Libre de Nitrógeno)',
            'ceniza' => 'Ceniza',
        ];

        $macrominerales = [
            'calcio' => 'Calcio (Ca)',
            'fosforo' => 'Fósforo (P)',
            'sodio' => 'Sodio (Na)',
            'potasio' => 'Potasio (K)',
            'cloro' => 'Cloro (Cl)',
            'magnesio' => 'Magnesio (Mg)',
            'azufre' => 'Azufre (S)',
        ];

        $microminerales = [
            'hierro' => 'Hierro (Fe)',
            'cobre' => 'Cobre (Cu)',
            'zinc' => 'Zinc (Zn)',
            'manganeso' => 'Manganeso (Mn)',
            'selenio' => 'Selenio (Se)',
            'yodo' => 'Yodo (I)',
            'cobalto' => 'Cobalto (Co)',
        ];

        $energias = [
            'energia_digestible' => 'Energía Digestible',
            'energia_metabolizable' => 'Energía Metabolizable',
            'energia_neta' => 'Energía Neta',
        ];

        // Arrays adicionales para la sección de aminoácidos diarios
        $aminoacidosDiarios = [
            'arginina' => 'Arginina',
            'glicina' => 'Glicina',
            'histidina' => 'Histidina',
            'isoleucina' => 'Isoleucina',
            'leucina' => 'Leucina',
            'lisina' => 'Lisina',
            'metionina' => 'Metionina',
            'cistina' => 'Cistina',
            'fenilalanina' => 'Fenilalanina',
            'tirosina' => 'Tirosina',
            'treonina' => 'Treonina',
            'triptofano' => 'Triptófano',
            'valina' => 'Valina',
        ];

        // Arrays para vitaminas
        $vitaminasLiposolubles = [
            'vitamina_a' => 'Vitamina A',
            'vitamina_d' => 'Vitamina D',
            'vitamina_e' => 'Vitamina E',
            'vitamina_k' => 'Vitamina K',
        ];

        $vitaminasHidrosolubles = [
            'biotina' => 'Biotina',
            'colina' => 'Colina',
            'acido_folico' => 'Ácido Fólico',
            'niacina' => 'Niacina',
            'acido_pantotenico' => 'Ácido Pantoténico',
            'riboflavina' => 'Riboflavina',
            'tiamina' => 'Tiamina',
            'vitamina_b6' => 'Vitamina B6',
            'vitamina_b12' => 'Vitamina B12',
        ];

        // Otros nutrientes
        $otrosNutrientes = [
            'acido_linoleico' => ['nombre' => 'Ácido Linoleico', 'unidad' => '%'],
            'azufre' => ['nombre' => 'Azufre', 'unidad' => 'g'],
            'xantofilas' => ['nombre' => 'Xantofilas', 'unidad' => 'mg'],
            'antioxidante' => ['nombre' => 'Antioxidante', 'unidad' => 'mg'],
            'metionina_cistina' => ['nombre' => 'Metionina + Cistina', 'unidad' => '%'],
            'acido_araquidonico' => ['nombre' => 'Ácido Araquidónico', 'unidad' => '%'],
            'p_d_perros' => ['nombre' => 'P.D. Perros', 'unidad' => 'g'],
            'acido_ascorbico' => ['nombre' => 'Ácido Ascórbico', 'unidad' => 'mg'],
            'globulina' => ['nombre' => 'Globulina', 'unidad' => '%'],
        ];

        // Tipos de alimentos para tolerancia
        $tiposAlimentos = [
            'energeticos' => ['icon' => 'flash_on', 'color' => 'warning', 'nombre' => 'Energéticos'],
            'proteicos' => ['icon' => 'fitness_center', 'color' => 'danger', 'nombre' => 'Proteicos'],
            'forrajes_verdes' => ['icon' => 'grass', 'color' => 'success', 'nombre' => 'Forrajes Verdes'],
            'forrajes_secos' => ['icon' => 'eco', 'color' => 'brown', 'nombre' => 'Forrajes Secos'],
            'ensilajes' => ['icon' => 'agriculture', 'color' => 'secondary', 'nombre' => 'Ensilajes'],
            'minerales' => ['icon' => 'terrain', 'color' => 'info', 'nombre' => 'Minerales'],
            'vitaminas' => ['icon' => 'biotech', 'color' => 'purple', 'nombre' => 'Vitaminas'],
            'aditivos' => ['icon' => 'science', 'color' => 'dark', 'nombre' => 'Aditivos'],
        ];

        return view('dashboard.animales.index', compact(
            'especies',
            'aminoacidos',
            'minerales',
            'vitaminas',
            'tipos',
            'weendeNutrientes',
            'macrominerales',
            'microminerales',
            'energias',
            'aminoacidosDiarios',
            'vitaminasLiposolubles',
            'vitaminasHidrosolubles',
            'otrosNutrientes',
            'tiposAlimentos'
        ));
    }

    // El resto de los métodos permanecen igual...
    public function store(StoreAnimalRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Crear el tipo de animal
            $tipo = TipoAnimal::create([
                'especie_id' => $validated['especie_id'],
                'nombre' => $validated['nombre'],
                'raza_linea' => $validated['raza_linea'] ?? null,
                'producto_final' => $validated['producto_final'] ?? null,
                'sistema_produccion' => $validated['sistema_produccion'] ?? 'intensivo',
                'etapa_especifica' => $validated['etapa_especifica'] ?? null,
                'edad_semanas' => $validated['edad_semanas'] ?? null,
                'peso_minimo_kg' => $validated['peso_minimo_kg'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'activo' => $validated['activo'] ?? true,
            ]);

            // Crear requerimiento nutricional base
            $reqData = $validated['requerimiento'] ?? [];
            $requerimiento = RequerimientoNutricional::create([
                'tipo_animal_id' => $tipo->id,
                'descripcion' => $reqData['descripcion'] ?? 'Base',
                'fuente' => $reqData['fuente'] ?? null,
                'consumo_esperado_kg_dia' => $reqData['consumo_esperado_kg_dia'] ?? null,
                'preferido' => $reqData['preferido'] ?? false,
                'activo' => $reqData['activo'] ?? true,
            ]);

            // Guardar WEENDE y energía si existen
            if (isset($validated['requerimientos_diarios']['weende'])) {
                $requerimiento->update([
                    'humedad' => $validated['requerimientos_diarios']['weende']['humedad'] ?? null,
                    'materia_seca' => $validated['requerimientos_diarios']['weende']['materia_seca'] ?? null,
                    'proteina_cruda' => $validated['requerimientos_diarios']['weende']['proteina_cruda'] ?? null,
                    'fibra_bruta' => $validated['requerimientos_diarios']['weende']['fibra_bruta'] ?? null,
                    'extracto_etereo' => $validated['requerimientos_diarios']['weende']['extracto_etereo'] ?? null,
                    'eln' => $validated['requerimientos_diarios']['weende']['eln'] ?? null,
                    'ceniza' => $validated['requerimientos_diarios']['weende']['ceniza'] ?? null,
                ]);
            }

            // Guardar energía si existe
            if (isset($validated['requerimientos_diarios']['energia'])) {
                $requerimiento->update([
                    'energia_digestible' => $validated['requerimientos_diarios']['energia']['energia_digestible'] ?? null,
                    'energia_metabolizable' => $validated['requerimientos_diarios']['energia']['energia_metabolizable'] ?? null,
                    'energia_neta' => $validated['requerimientos_diarios']['energia']['energia_neta'] ?? null,
                ]);
            }

            // Guardar aminoácidos
            foreach (($validated['aminoacidos'] ?? []) as $aminoId => $vals) {
                if (isset($vals['valor'])) {
                    $requerimiento->aminoacidos()->attach($aminoId, [
                        'valor' => $vals['valor'],
                        'unidad' => '%',
                    ]);
                }
            }

            // Guardar minerales
            foreach (($validated['minerales'] ?? []) as $mineralId => $vals) {
                if (isset($vals['valor'])) {
                    $requerimiento->minerales()->attach($mineralId, [
                        'valor' => $vals['valor'],
                        'unidad' => $vals['unidad'] ?? 'mg/kg',
                    ]);
                }
            }

            // Guardar vitaminas
            foreach (($validated['vitaminas'] ?? []) as $vitaminaId => $vals) {
                if (isset($vals['valor'])) {
                    $requerimiento->vitaminas()->attach($vitaminaId, [
                        'valor' => $vals['valor'],
                        'unidad' => $vals['unidad'] ?? 'UI/kg',
                    ]);
                }
            }

            // Guardar tolerancia a alimentos
            foreach (($validated['tolerancia_alimentos'] ?? []) as $tipo => $data) {
                if (isset($data['porcentaje_maximo'])) {
                    // Aquí necesitarías crear una tabla para tolerancias
                    // Ej: ToleranciaAlimento::create([...])
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Animal registrado correctamente',
                'data' => $tipo->load('especie', 'requerimientos'),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'No se pudo registrar el animal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
