@extends('layouts.material')

@section('title', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-icons opacity-10">groups</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Personas</p>
          <h4 class="mb-0">{{ \App\Models\Personas::count() }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3 d-flex justify-content-between">
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-icons opacity-10">local_shipping</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Proveedores</p>
          <h4 class="mb-0">{{ \App\Models\Proveedor::count() }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3 d-flex justify-content-between">
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-icons opacity-10">history</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Bitácora</p>
          <h4 class="mb-0">—</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-6 mt-4 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
          <div class="chart"><canvas id="md-line" height="170"></canvas></div>
        </div>
      </div>
      <div class="card-body">
        <h6 class="mb-0">Actividad</h6>
        <p class="text-sm">Métrica de ejemplo</p>
      </div>
    </div>
  </div>
  <div class="col-lg-6 mt-4 mb-4">
    <div class="card z-index-2">
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
          <div class="chart"><canvas id="md-bars" height="170"></canvas></div>
        </div>
      </div>
      <div class="card-body">
        <h6 class="mb-0">Registros</h6>
        <p class="text-sm">Métrica de ejemplo</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card z-index-2">
      <div class="card-header pb-0">
        <h6 class="mb-0">Mapa</h6>
      </div>
      <div class="card-body">
        <div class="chart">
          <canvas id="md-geo" class="geo-canvas"></canvas>
        </div>
      </div>
    </div>
  </div>
  
</div>

<!-- Modal Info Geo -->
<div class="modal fade" id="geoInfoModal" tabindex="-1" aria-labelledby="geoInfoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="geoInfoLabel"><i class="material-icons me-2">map</i><span class="geo-title">Detalle</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="geo-content">
          <p class="text-sm text-muted mb-2">Seleccione un estado para ver detalles.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal"><span class="material-icons align-middle me-1" style="font-size:18px">close</span>Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const l = document.getElementById('md-line');
  const b = document.getElementById('md-bars');
  const g = document.getElementById('md-geo');
  const stateCities = {
    'Distrito Capital': ['Caracas', 'El Junquito'],
    'Miranda': ['Los Teques', 'Guarenas', 'Guatire'],
    'Carabobo': ['Valencia', 'Naguanagua', 'Puerto Cabello'],
    'Zulia': ['Maracaibo', 'Cabimas', 'Ciudad Ojeda']
  };

  const getFeatureName = (props={}) => props.name || props.NOMBRE || props.state || props.estado || props.Estado || '—';

  const showGeoModal = (name, props={}) => {
    const modalEl = document.getElementById('geoInfoModal');
    if (!modalEl) return;
    const titleEl = modalEl.querySelector('.geo-title');
    const bodyEl = modalEl.querySelector('.geo-content');
    if (titleEl) titleEl.textContent = name;
    const cities = stateCities[name] || [];
    if (bodyEl) {
      bodyEl.innerHTML = cities.length
        ? `<p class="mb-1"><strong>Ciudades:</strong></p><ul class="mb-0">${cities.map(c => `<li>${c}</li>`).join('')}</ul>`
        : `<p class="mb-0 text-muted">No hay datos de ciudades para este estado.</p>`;
    }
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  };
  if (window.Chart && l) {
    new Chart(l.getContext('2d'), {
      type: 'line',
      data: { labels: ['L','M','M','J','V','S','D'], datasets: [{ label:'Visitas', data:[12,19,3,5,2,3,10], borderColor:'#fff', backgroundColor:'rgba(255,255,255,.2)' }] },
      options: { plugins:{ legend:{ display:false } }, scales: { y: { beginAtZero:true } } }
    });
  }
  if (window.Chart && b) {
    new Chart(b.getContext('2d'), {
      type: 'bar',
      data: { labels: ['P','Q','R','S','T','U'], datasets: [{ label:'Altas', data:[5,4,6,7,3,4], backgroundColor:'#fff' }] },
      options: { plugins:{ legend:{ display:false } }, scales: { y: { beginAtZero:true } } }
    });
  }

  // Geo chart (choropleth) usando chartjs-chart-geo con prioridad al GeoJSON local
  if (window.Chart && window.ChartGeo && g) {
    const renderChoropleth = (features, opts = {}) => {
      const ctx = g.getContext('2d');
      return new Chart(ctx, {
        type: 'choropleth',
        data: {
          labels: features.map(d => (d.properties && (d.properties.name || d.properties.NOMBRE || d.properties.state || d.properties.estado)) || d.id || '—'),
          datasets: [{
            label: opts.label || 'Regiones',
            outline: features,
            data: features.map(d => ({ feature: d, value: Math.random() * 100 }))
          }]
        },
        options: {
          showOutline: true,
          showGraticule: opts.graticule ?? false,
          responsive: true,
          maintainAspectRatio: true,
          aspectRatio: opts.aspectRatio || 2,
          scales: {
            projection: {
              axis: 'x',
              projection: opts.projection || 'mercator'
            }
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: (ctx) => {
                  const props = ctx.raw.feature.properties || {};
                  const name = props.name || props.NOMBRE || props.state || props.estado || '—';
                  const val = (ctx.raw.value ?? 0).toFixed(1);
                  return `${name}: ${val}`;
                }
              }
            }
          },
          onClick: (evt, elements, chart) => {
            if (!elements || !elements.length) return;
            const index = elements[0].index;
            const raw = chart.data.datasets[0].data[index];
            const props = (raw && raw.feature && raw.feature.properties) || {};
            const name = getFeatureName(props);
            showGeoModal(name, props);
          }
        }
      });
    };

    // 1) Intentar el GeoJSON local de Venezuela
    fetch("{{ route('geo.ve') }}")
      .then(r => { if (!r.ok) throw new Error('local not found'); return r.json(); })
      .then(json => {
        const features = (json && json.type === 'FeatureCollection') ? json.features : [];
        if (!features.length) throw new Error('no features');
        renderChoropleth(features, { label: 'Venezuela', projection: 'mercator', graticule: false });
      })
      // 2) Fallback: mapa mundial desde world-atlas (TopoJSON)
      .catch(() => fetch('https://cdn.jsdelivr.net/npm/world-atlas@2/countries-50m.json')
        .then(r => r.json())
        .then(topology => {
          const countries = ChartGeo.topojson.feature(topology, topology.objects.countries).features;
          renderChoropleth(countries, { label: 'Países (fallback)', projection: 'equalEarth', graticule: true });
        })
      )
      .catch(() => { /* Silenciar si ambos fallan */ });
  }
});
</script>
@endpush
