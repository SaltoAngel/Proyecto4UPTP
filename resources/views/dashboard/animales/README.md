# Notas de integración para Animales

## Objetivo
Que el formulario de crear animal funcione sin fallbacks en Blade, usando los datos reales que vienen del backend y respetando las tablas/migraciones existentes.

## Qué pasar a la vista desde `AnimalesController@index`
En el `compact` del return agrega estos arrays de etiquetas (son solo textos para iterar en Blade):

```php
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

return view('dashboard.animales.index', compact(
  'especies','aminoacidos','minerales','vitaminas','tipos',
  'weendeNutrientes','macrominerales','microminerales','energias'
));
```

## Dónde se usan en Blade
- `resources/views/dashboard/animales/modals/crear.blade.php`: sección "Requerimientos Nutricionales Diarios" recorre `$weendeNutrientes`, `$macrominerales`, `$microminerales` y `$energias` para pintar inputs.
- El resto de catálogos (`aminoacidos`, `minerales`, `vitaminas`, `especies`, `tipos`) ya se cargan desde modelos y se usan en selects/tablas.

## Cómo viajan los datos al guardar
1. Formulario envía:
   - Datos base del tipo de animal (`tipos_animal`).
   - `requerimiento[...]`: WEENDE y energías directos a `requerimientos_nutricionales`.
   - `aminoacidos[id][valor_*]`: pivote `requerimiento_aminoacido`.
   - `minerales[id][valor_*]`: pivote `requerimiento_mineral`.
   - `vitaminas[id][valor_*]`: pivote `requerimiento_vitamina`.
2. `StoreAnimalRequest` valida rangos y presencia de `especie_id`/`nombre`.
3. `AnimalesController@store` crea `tipos_animal`, luego `requerimientos_nutricionales`, y sincroniza pivotes de aminoácidos, minerales y vitaminas con los arrays recibidos.

## Tablas/migraciones clave (resumen funcional)
- `tipos_animal`: tipo y etapa; FK a `especies`; soft deletes.
- `requerimientos_nutricionales`: WEENDE + energías + consumo esperado; FK a `tipos_animal`; soft deletes.
- Pivotes de requerimientos: `requerimiento_aminoacido`, `requerimiento_mineral`, `requerimiento_vitamina` (almacenan min/max/recomendado y unidad por nutriente).
- Catálogos: `aminoacidos`, `minerales`, `vitaminas` (con orden, unidad, esencialidad).
- Materias primas y composiciones: `materias_primas`; `composiciones_nutricionales` (análisis prox); desgloses `composicion_aminoacido`, `composicion_mineral`, `composicion_vitamina`.
- Energía por especie y composición: `valores_energeticos` (digestible/metabolizable/neta por especie y materia prima).
- Tolerancias por tipo de alimento: `alimentos` (tipo y porcentaje máximo recomendado).

## Pasos para que el modal funcione sin errores
1) Añade los arrays de etiquetas al `compact` del `index` (ver bloque de código arriba).
2) Quita cualquier fallback en Blade (ya no se necesitan si vienen del controlador).
3) Corre `php artisan view:clear` si ves caché de vistas.
4) Probar en `/dashboard/animales`: abrir modal de crear y verificar que se rendericen los inputs de WEENDE, macro/micro minerales y energías sin errores.

## Notas rápidas
- Los campos WEENDE y energías en la tabla `requerimientos_nutricionales` se llenan con el array `requerimiento[...]` del form.
- Los pivotes usan `sync` en el controlador: solo se guardan los nutrientes enviados.
- Si agregas nuevos nutrientes o energías, amplía los arrays de etiquetas y (si aplica) las columnas en la migración correspondiente.
