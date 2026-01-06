const fs = require('fs');
const path = 'C:/Users/Informatica/Downloads/Base_cartografica1.geojson';
const out = 'C:/xampp/htdocs/ProyectoUPTP4/public/data/venezuela-geo-simple.json';

const raw = JSON.parse(fs.readFileSync(path, 'utf8'));
const estadosMap = new Map();

for (const f of raw.features || []) {
  const p = f.properties || {};
  const estadoKey = String(p.COD_ESTADO || '').padStart(2, '0');
  const muniKey = String(p.COD_MUNIC || '').padStart(2, '0');
  const estadoNombre = String(p.ESTADO || '').trim();
  const muniNombre = String(p.MUNICIPIO || '').trim();
  const capEstado = String(p.CAP_ESTADO || '').trim();
  const capMuni = String(p.CAP_MUNIC || '').trim();
  const ciMuni = String(p.CI_MUNICI || '').trim();
  const nota = String(p.NOTA || '').trim();

  if (!estadoKey || !estadoNombre || !muniKey || !muniNombre) continue;

  if (!estadosMap.has(estadoKey)) {
    estadosMap.set(estadoKey, {
      codigo: estadoKey,
      nombre: estadoNombre,
      capital: capEstado,
      municipios: [],
    });
  }

  const estado = estadosMap.get(estadoKey);
  const exists = estado.municipios.some((m) => m.codigo === muniKey);
  if (!exists) {
    estado.municipios.push({
      codigo: muniKey,
      codigo_completo: ciMuni,
      nombre: muniNombre,
      capital: capMuni,
      nota: nota || undefined,
    });
  }
}

const result = {
  source: 'Base_cartografica1.geojson (propiedades)',
  generated_at: new Date().toISOString(),
  estados: Array.from(estadosMap.values())
    .map((e) => ({
      ...e,
      municipios: e.municipios.sort((a, b) => a.nombre.localeCompare(b.nombre, 'es')),
    }))
    .sort((a, b) => a.nombre.localeCompare(b.nombre, 'es')),
};

fs.mkdirSync(require('path').dirname(out), { recursive: true });
fs.writeFileSync(out, JSON.stringify(result, null, 2));
console.log('Archivo generado:', out, 'Estados:', result.estados.length);
