@extends('layouts.material')

@section('title', 'Dashboard')

{{--
  Vista: Dashboard (Material)
  Propósito: Mostrar métricas rápidas, gráficos (línea y barras), mapa choropleth y Top 5 clientes.
  Convenciones:
    - Skeleton loading: se usa via helpers globales skeletonAttach/skeletonReady en el script inferior.
    - Datos: llegan desde DashboardController (personasCount, proveedoresCount, bitacoraCount, topClientes).
    - Mapa: intenta primero GeoJSON local (route('geo.ve')) y hace fallback a world-atlas.
    - Modal: al hacer click en un estado, se abre una modal con detalle de ejemplo (ciudades demo).
--}}
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
          <h4 class="mb-0">{{ $personasCount ?? '—' }}</h4>
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
          <h4 class="mb-0">{{ $proveedoresCount ?? '—' }}</h4>
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
          <h4 class="mb-0">{{ $bitacoraCount ?? '—' }}</h4>
        </div>
      </div>
      <hr class="dark horizontal my-0">
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-8 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0"><i class="material-icons me-2" style="font-size:18px">event</i> Calendario</h6>
      </div>
      <div class="card-body">
        <div id="calendar" class="w-100"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 mb-4">
    <div class="card h-80">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0"><i class="material-icons me-2" style="font-size:18px">donut_large</i>Tareas por tipo</h6>
      </div>
      <div class="card-body">
        <div class="chart">
          <canvas id="md-donut" height="240"></canvas>
        </div>
      </div>
    </div>
  </div>
  <style>
    #calendar .fc-toolbar-title { font-size: 1rem; }
    #calendar { min-height: 620px; }
  </style>
</div>

<div class="row mt-4">
  <div class="col-lg-6 mt-4 mb-4">
    <div class="card z-index-2">
      {{-- Gráfico de línea con skeleton controlado por JS --}}
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
          <div class="chart">
            <canvas id="md-line" height="170"></canvas>
          </div>
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
      {{-- Gráfico de barras con skeleton controlado por JS --}}
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
          <div class="chart">
            <canvas id="md-bars" height="170"></canvas>
          </div>
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
  <div class="col-lg-8 mb-4">
    <div class="card z-index-2 h-100">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Mapa Nacional</h6>
        <div class="btn-group btn-group-sm" role="group" aria-label="Zoom controls">
          <button type="button" class="btn btn-light" id="geo-zoom-in" title="Acercar">+
          </button>
          <button type="button" class="btn btn-light" id="geo-zoom-out" title="Alejar">-
          </button>
          <button type="button" class="btn btn-secondary" id="geo-zoom-reset" title="Reset">Reset
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="chart">
          {{-- Canvas del mapa (choropleth) con skeleton controlado por JS --}}
          <canvas id="md-geo" class="geo-canvas"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0 d-flex align-items-center justify-content-between">
        <h6 class="mb-0"><i class="material-icons me-2" style="font-size:18px">leaderboard</i>Top 5 Clientes</h6>
      </div>
      <div class="card-body pt-3">
        {{-- Lista Top 5: se muestra tras skeletonAttach/skeletonReady --}}
        @php
          $top = isset($topClientes) && is_iterable($topClientes) ? $topClientes : [
            ['nombre' => 'Cliente A', 'total' => 120],
            ['nombre' => 'Cliente B', 'total' => 95],
            ['nombre' => 'Cliente C', 'total' => 78],
            ['nombre' => 'Cliente D', 'total' => 64],
            ['nombre' => 'Cliente E', 'total' => 51],
          ];
        @endphp
        <ul id="top-list" class="list-group">
          @foreach($top as $i => $c)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>
                <span class="badge bg-primary me-2">{{ $i+1 }}</span>
                {{ is_array($c) ? ($c['nombre'] ?? ($c['name'] ?? '—')) : ($c->nombre ?? ($c->name ?? '—')) }}
              </span>
              <span class="badge bg-gradient-success">{{ is_array($c) ? ($c['total'] ?? 0) : ($c->total ?? 0) }}</span>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Modal Info Geo: muestra detalle del estado clickeado -->
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
{{--
  Script de la página:
    - Inicializa gráficos (Chart.js) y oculta skeletons al terminar.
    - Renderiza choropleth con Chart.js Geo; onClick abre la modal.
    - Usa helpers globales: skeletonAttach(id, opts) y skeletonReady(id).
--}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  const l = document.getElementById('md-line');
  const b = document.getElementById('md-bars');
  const g = document.getElementById('md-geo');
  const topList = document.getElementById('top-list');
  const stateCities = {
    'Distrito Capital': ['Caracas', 'El Junquito'],
    'Miranda': ['Los Teques', 'Guarenas', 'Guatire'],
    'Carabobo': ['Valencia', 'Naguanagua', 'Puerto Cabello'],
    'Zulia': ['Maracaibo', 'Cabimas', 'Ciudad Ojeda']
  };

  const getFeatureName = (props={}) => props.name || props.NOMBRE || props.state || props.estado || props.Estado || '—';
  let geoChart = null;

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
    window.skeletonAttach('md-line', { type: 'rect', height: 170 });
    new Chart(l.getContext('2d'), {
      type: 'line',
      data: { labels: ['L','M','M','J','V','S','D'], datasets: [{ label:'Visitas', data:[12,19,3,5,2,3,10], borderColor:'#fff', backgroundColor:'rgba(255,255,255,.2)' }] },
      options: { plugins:{ legend:{ display:false } }, scales: { y: { beginAtZero:true } } }
    });
    window.skeletonReady('md-line');
  }
  if (window.Chart && b) {
    window.skeletonAttach('md-bars', { type: 'rect', height: 170 });
    new Chart(b.getContext('2d'), {
      type: 'bar',
      data: { labels: ['P','Q','R','S','T','U'], datasets: [{ label:'Altas', data:[5,4,6,7,3,4], backgroundColor:'#fff' }] },
      options: { plugins:{ legend:{ display:false } }, scales: { y: { beginAtZero:true } } }
    });
    window.skeletonReady('md-bars');
  }
  // Lista Top: mostrar con skeleton breve
  if (topList) {
    window.skeletonAttach('top-list', { type: 'text' });
    window.skeletonReady('top-list');
  }

  // Geo chart (choropleth) usando chartjs-chart-geo con prioridad al GeoJSON local
  if (window.Chart && window.ChartGeo && g) {
    window.skeletonAttach('md-geo', { type: 'rect', height: 280 });
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
            zoom: {
              pan: { enabled: true, mode: 'xy' },
              zoom: {
                wheel: { enabled: true, speed: 0.1 },
                pinch: { enabled: true },
                mode: 'xy'
              }
            },
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
        geoChart = renderChoropleth(features, { label: 'Venezuela', projection: 'mercator', graticule: false });
        window.skeletonReady('md-geo');
      })
      // 2) Fallback: mapa mundial desde world-atlas (TopoJSON)
      .catch(() => fetch('https://cdn.jsdelivr.net/npm/world-atlas@2/countries-50m.json')
        .then(r => r.json())
        .then(topology => {
          const countries = ChartGeo.topojson.feature(topology, topology.objects.countries).features;
          geoChart = renderChoropleth(countries, { label: 'Países (fallback)', projection: 'equalEarth', graticule: true });
          window.skeletonReady('md-geo');
        })
      )
      .catch(() => { /* Silenciar si ambos fallan */ setTimeout(() => { window.skeletonReady('md-geo'); }, 3000); });
  }

  // Controles de zoom del mapa
  const zoomInBtn = document.getElementById('geo-zoom-in');
  const zoomOutBtn = document.getElementById('geo-zoom-out');
  const zoomResetBtn = document.getElementById('geo-zoom-reset');
  const zoomStep = 1.2;
  if (zoomInBtn) zoomInBtn.addEventListener('click', () => { try { geoChart && geoChart.zoom(zoomStep); } catch(e){} });
  if (zoomOutBtn) zoomOutBtn.addEventListener('click', () => { try { geoChart && geoChart.zoom(1/zoomStep); } catch(e){} });
  if (zoomResetBtn) zoomResetBtn.addEventListener('click', () => { try { geoChart && geoChart.resetZoom(); } catch(e){} });

  // FullCalendar: inicialización básica con eventos de ejemplo
  try {
    if (window.FullCalendar && document.getElementById('calendar')) {
      const calendarEl = document.getElementById('calendar');
      // Fuente base de eventos del calendario (también usada para el donut)
      const calendarEvents = [
        { title: 'Recepción programada', start: new Date().toISOString().slice(0,10) },
        { title: 'Orden de compra', start: new Date(Date.now() + 86400000).toISOString().slice(0,10) },
        { title: 'Reunión proveedor', start: new Date(Date.now() + 3*86400000).toISOString().slice(0,10) }
      ];

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 'auto',
        expandRows: true,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        locale: 'es',
        firstDay: 1,
        navLinks: true,
        selectable: true,
        selectMirror: true,
        select: (info) => {
          Swal && Swal.fire({ icon: 'info', title: 'Rango seleccionado', text: `${info.startStr} → ${info.endStr}` });
        },
        eventClick: (info) => {
          info.jsEvent.preventDefault();
          const e = info.event;
          Swal && Swal.fire({ icon: 'info', title: e.title, text: e.start?.toLocaleString('es-VE') });
        },
        events: calendarEvents
      });
      calendar.render();

      // Doughnut: calcular desde calendar.getEvents() y actualizar en vivo
      if (window.Chart && document.getElementById('md-donut')) {
        let donutChart = null;
        const donutEl = document.getElementById('md-donut');
        const typeFor = (title = '') => {
          const t = (title || '').toLowerCase();
          if (t.includes('recepci')) return 'Recepción';
          if (t.includes('orden')) return 'Orden';
          if (t.includes('reuni')) return 'Reunión';
          return 'Otros';
        };
        const types = ['Recepción','Orden','Reunión','Otros'];

        const rebuildDonut = () => {
          try {
            const counts = { 'Recepción':0, 'Orden':0, 'Reunión':0, 'Otros':0 };
            const evts = calendar.getEvents();
            evts.forEach(e => { counts[typeFor(e.title)]++; });
            if (!donutChart) {
              window.skeletonAttach('md-donut', { type: 'rect', height: 240 });
              donutChart = new Chart(donutEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                  labels: types,
                  datasets: [{
                    data: types.map(k => counts[k]),
                    backgroundColor: ['#42a5f5','#66bb6a','#ffca28','#ab47bc'],
                    borderWidth: 1,
                  }]
                },
                options: {
                  plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.parsed}` } }
                  },
                  cutout: '65%'
                }
              });
              window.skeletonReady('md-donut');
            } else {
              donutChart.data.datasets[0].data = types.map(k => counts[k]);
              donutChart.update();
            }
          } catch (e) { console.warn('Donut update:', e); }
        };

        // Actualizar cuando cambien los eventos del calendario
        calendar.on('eventAdd', rebuildDonut);
        calendar.on('eventChange', rebuildDonut);
        calendar.on('eventRemove', rebuildDonut);
        // Inicial tras render
        calendar.render();
        rebuildDonut();
      } else {
        // Si no hay donut, al menos renderizamos el calendario
        calendar.render();
      }
    }
  } catch (e) { console.warn('FullCalendar init:', e); }
});
</script>
@endpush

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
@endpush
