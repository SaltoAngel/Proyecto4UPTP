<!-- resources/views/dashboard/animales/modals/editar.blade.php -->
<div class="modal fade" id="editarAnimalModal" tabindex="-1" aria-labelledby="editarAnimalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarAnimalLabel">Editar Animal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditarAnimal" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editNombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="editNombre" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label for="editEtapa" class="form-label">Etapa (código)</label>
              <input type="text" class="form-control" id="editEtapa" name="codigo_etapa">
            </div>
            <div class="col-md-6">
              <label for="editEdadMin" class="form-label">Edad mínima (días)</label>
              <input type="number" class="form-control" id="editEdadMin" name="edad_minima_dias" min="0">
            </div>
            <div class="col-md-6">
              <label for="editEdadMax" class="form-label">Edad máxima (días)</label>
              <input type="number" class="form-control" id="editEdadMax" name="edad_maxima_dias" min="0">
            </div>
            <div class="col-md-6">
              <label for="editPesoMin" class="form-label">Peso mínimo (kg)</label>
              <input type="number" step="0.01" class="form-control" id="editPesoMin" name="peso_minimo_kg" min="0">
            </div>
            <div class="col-md-6">
              <label for="editPesoMax" class="form-label">Peso máximo (kg)</label>
              <input type="number" step="0.01" class="form-control" id="editPesoMax" name="peso_maximo_kg" min="0">
            </div>
            <div class="col-12 form-check mt-2">
              <input class="form-check-input" type="checkbox" id="editActivo" name="activo" value="1">
              <label class="form-check-label" for="editActivo">Activo</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="material-icons me-2">close</i>Cancelar
          </button>
          <button type="submit" class="btn btn-success">
            <i class="material-icons me-2">save</i>Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
