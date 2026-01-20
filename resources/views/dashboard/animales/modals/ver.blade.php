<!-- resources/views/dashboard/animales/modals/ver.blade.php -->
<div class="modal fade" id="verAnimalModal" tabindex="-1" aria-labelledby="verAnimalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verAnimalLabel">Detalles del Animal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="text-muted">Especie</label>
            <div id="verEspecie">—</div>
          </div>
          <div class="col-md-6">
            <label class="text-muted">Nombre</label>
            <div id="verNombre">—</div>
          </div>
          <div class="col-md-6">
            <label class="text-muted">Etapa</label>
            <div id="verEtapa">—</div>
          </div>
          <div class="col-md-6">
            <label class="text-muted">Estado</label>
            <div id="verEstado">—</div>
          </div>
          <div class="col-md-6">
            <label class="text-muted">Edad (días)</label>
            <div id="verEdad">—</div>
          </div>
          <div class="col-md-6">
            <label class="text-muted">Peso (kg)</label>
            <div id="verPeso">—</div>
          </div>
          <div class="col-12">
            <label class="text-muted">Descripción</label>
            <div id="verDescripcion" class="text-wrap">—</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="material-icons me-2">close</i>Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
