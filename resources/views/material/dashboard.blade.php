@extends('layouts.material')

@section('title', 'Material Dashboard (Demo)')

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
        <a href="{{ route('dashboard.personas.index') }}" class="btn btn-sm btn-primary">Ir</a>
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
        <a href="{{ route('dashboard.proveedores.index') }}" class="btn btn-sm btn-success">Ir</a>
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
      <div class="card-footer p-3 d-flex justify-content-between">
        <a href="{{ route('dashboard.bitacora.index') }}" class="btn btn-sm btn-info text-white">Ir</a>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-header p-3 pt-2">
        <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
          <i class="material-icons opacity-10">logout</i>
        </div>
        <div class="text-end pt-1">
          <p class="text-sm mb-0 text-capitalize">Sesión</p>
          <h6 class="mb-0 text-muted">{{ auth()->user()->name ?? 'Usuario' }}</h6>
        </div>
      </div>
      <hr class="dark horizontal my-0">
      <div class="card-footer p-3 d-flex justify-content-between">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-2').submit();" class="btn btn-sm btn-dark">Salir</a>
        <form id="logout-form-2" action="{{ route('dashboard.logout') }}" method="GET" class="d-none"></form>
      </div>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const l = document.getElementById('md-line');
  const b = document.getElementById('md-bars');
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
});
</script>
@endpush
