# Esquema de migraciones (nutrición y animales)

## Especies
- Tabla: `especies` (PK `id`, soft deletes)
- Campos: `nombre` (100, único), `nombre_cientifico` (150), `codigo` (20, único), `descripcion` (text), `activo` (bool)
- Relaciones: `tipos_animal` FK a `especies`

## Tipos de animal
- Tabla: `tipos_animal` (PK `id`, soft deletes)
- Campos: `especie_id` (FK especies), `nombre` (100), `raza_linea` (100, nullable), `producto_final` (enum: leche, carne, huevos, doble_proposito, reproduccion, trabajo, lana, miel, otro), `sistema_produccion` (enum: intensivo, semi-intensivo, extensivo, organico, otro), `etapa_especifica` (200, nullable), `edad_semanas` (int, nullable), `peso_minimo_kg` (decimal 10,2, nullable), `descripcion` (text), `activo` (bool)
- Restricción única: combinación `especie_id, nombre, raza_linea, etapa_especifica, edad_semanas` (ajustada en migración 2026_01_25_195443)
- Relaciones: 1:N con `requerimientos_nutricionales`, y FK desde `alimentos` (columna agregada en 2026_01_25_195443)

## Requerimientos nutricionales
- Tabla: `requerimientos_nutricionales` (PK `id`, soft deletes)
- FK: `tipo_animal_id` -> `tipos_animal`
- Campos: `descripcion` (150, único por tipo), `fuente` (50), `comentario` (text), `consumo_esperado_kg_dia` (decimal 10,4), `preferido` (bool)
- WEENDE (%): `humedad`, `materia_seca`, `proteina_cruda`, `fibra_bruta`, `extracto_etereo`, `eln`, `ceniza` (decimal 8,4)
- Energía (Mcal/kg): `energia_digestible`, `energia_metabolizable`, `energia_neta`, `energia_digestible_ap`, `energia_metabolizable_ap` (decimal 10,4)
- Activo (bool)
- Relaciones: pivotes con aminoácidos, minerales, vitaminas

## Aminoácidos
- Tabla: `aminoacidos` (PK `id`)
- Campos: `nombre` (100, único), `abreviatura` (10), `tipo` (20), `funcion` (text), `esencial` (bool), `orden` (int)
- Pivote `requerimiento_aminoacido`: FK `requerimiento_id` -> requerimientos_nutricionales, FK `aminoacido_id` -> aminoacidos; valores `valor_min`, `valor_max`, `valor_recomendado` (decimal 10,6) y `unidad` (20). Único por requerimiento+aminoácido.
- Composición de materias primas: `composicion_aminoacido` FK `composicion_id` -> composiciones_nutricionales, FK `aminoacido_id`; valores `valor`, `valor_min`, `valor_max` (decimal), `unidad` (20), `observaciones` (text). Único por composición+aminoácido.

## Minerales
- Tabla: `minerales` (PK `id`)
- Campos: `nombre` (100, único), `unidad` (20, default mg/kg), `simbolo` (10), `funcion` (text), `esencial` (bool), `orden` (int)
- Pivote `requerimiento_mineral`: FK a requerimientos_nutricionales y minerales; `valor_min`, `valor_max`, `valor_recomendado` (decimal 10,6), `unidad` (20); único por requerimiento+mineral.
- Composición de materias primas: `composicion_mineral` FK `composicion_id` -> composiciones_nutricionales, FK `mineral_id`; `valor`, `valor_min`, `valor_max` (decimal 12,6), `unidad` (20), `observaciones`; único por composición+mineral.

## Vitaminas
- Tabla: `vitaminas` (PK `id`)
- Campos: `nombre` (100, único), `tipo` (20), `unidad` (20, default UI/kg), `funcion` (text), `esencial` (bool), `orden` (int)
- Pivote `requerimiento_vitamina`: FK a requerimientos_nutricionales y vitaminas; `valor_min`, `valor_max`, `valor_recomendado` (decimal 10,6), `unidad` (20); único por requerimiento+vitamina.
- Composición de materias primas: `composicion_vitamina` FK `composicion_id` -> composiciones_nutricionales, FK `vitamina_id`; `valor`, `valor_min`, `valor_max` (decimal 12,6), `unidad` (20), `observaciones`; único por composición+vitamina.

## Materias primas y composiciones
- Tabla: `categorias_materia_prima` (PK `id`)
  - `nombre` (100, único), `codigo_nrc` (20), `descripcion` (text), `tipo` (enum: energetico, proteico, forraje_verde, forraje_seco, ensilaje, mineral, vitamina, aditivo, suplemento, otro), `activo` (bool), `orden` (int)
- Tabla: `materias_primas` (PK `id`, soft deletes)
  - FK `categoria_id` -> categorias_materia_prima (nullOnDelete)
  - Campos: `descripcion` (150), `codigo` (50, único), `nombre_comercial` (150), `nombre_cientifico` (150), `comentario` (text), flags `preferido`, `activo`, `disponible`, fechas varias, índices en descripcion/activo y categoria
- Tabla: `composiciones_nutricionales` (PK `id`)
  - FK `materia_prima_id` -> materias_primas (cascade)
  - Versionado: `version` (20, único por materia prima), `fecha_analisis`, `laboratorio`, `metodo_analisis`
  - WEENDE (% base seca): `humedad`, `materia_seca`, `proteina_cruda`, `fibra_bruta`, `extracto_etereo`, `eln`, `ceniza` (decimal 8,4)
  - `observaciones` (text), `es_predeterminado` (bool)
- Tabla: `valores_energeticos` (PK `id`)
  - FK `composicion_id` -> composiciones_nutricionales, FK `especie_id` -> especies
  - Campos: `tipo_energia` (30), `valor` (decimal 10,4), `unidad` (20, default Mcal/kg), `coeficiente_digestibilidad` (5,3), `metodo_calculo` (text)
  - Único por composición+especie+tipo_energia
- Tabla: `costos_materia_prima` (PK `id`)
  - FK `materia_prima_id` -> materias_primas (cascade)
  - Campos de costo: `costo_unitario` (12,4), `unidad_compra` (20), `factor_conversion` (10,4), `costo_kg` (stored), `moneda` (3), fechas vigencia/vencimiento, `proveedor` (150), `lote` (50), `observaciones` (text), `activo` (bool)
  - Índices en materia_prima_id+activo y fecha_vigencia
- Tabla: `restricciones_especie` (PK `id`)
  - FK `materia_prima_id` -> materias_primas, FK `especie_id` -> especies
  - Campos: `permitido` (bool), `restriccion_nivel` (enum ALTA/MEDIA/BAJA/NINGUNA), `razon_restriccion` (text), `observaciones` (text), `requiere_autorizacion` (bool)
  - Único por materia_prima+especie

## Tolerancias de alimentos
- Tabla: `alimentos` (PK `id`)
- Campos: `tipo` (string), `cantidad_maxima` (decimal 5,2) porcentaje máximo recomendado
- Migración 2026_01_25_195443 agrega `tipo_animal_id` (FK a `tipos_animal`, nullable, cascade on delete)

## Relaciones clave (resumen)
- `especies` 1:N `tipos_animal`
- `tipos_animal` 1:N `requerimientos_nutricionales`
- `requerimientos_nutricionales` N:M con `aminoacidos`, `minerales`, `vitaminas` mediante pivotes `requerimiento_*`
- `materias_primas` 1:N `composiciones_nutricionales` y 1:N `costos_materia_prima`
- `composiciones_nutricionales` N:M con `aminoacidos`, `minerales`, `vitaminas` mediante tablas `composicion_*`
- `composiciones_nutricionales` N:M `especies` vía `valores_energeticos`
- `materias_primas` N:M `especies` vía `restricciones_especie`

## Notas
- Todas las tablas mencionadas tienen timestamps; las que indican soft deletes lo soportan.
- Valores nutricionales usan decimales, revisar precisión/escala antes de migrar datos externos.
- Ajustar seeds para usar únicamente columnas existentes en estos esquemas.
