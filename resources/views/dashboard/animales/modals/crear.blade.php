<div class="modal fade" id="crearAnimalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Animal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCrearAnimal" action="{{ route('dashboard.animales.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-uppercase text-muted">Datos básicos</h6>
                            <hr class="mt-1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Especie <span class="text-danger">*</span></label>
                            <select name="especie_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($especies as $e)
                                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombre del tipo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Etapa / Código</label>
                            <input type="text" name="codigo_etapa" class="form-control" maxlength="20" placeholder="CRECIMIENTO, LACTANCIA, etc.">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Edad mínima (días)</label>
                            <input type="number" name="edad_minima_dias" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Edad máxima (días)</label>
                            <input type="number" name="edad_maxima_dias" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Peso mínimo (kg)</label>
                            <input type="number" step="0.01" name="peso_minimo_kg" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Peso máximo (kg)</label>
                            <input type="number" step="0.01" name="peso_maximo_kg" class="form-control" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activo" id="activoAnimal" checked value="1">
                                <label class="form-check-label" for="activoAnimal">Activo</label>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="text-uppercase text-muted">Requerimiento nutricional (base)</h6>
                            <hr class="mt-1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="requerimiento[descripcion]" class="form-control" maxlength="150" placeholder="Base, estándar, etc.">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fuente</label>
                            <input type="text" name="requerimiento[fuente]" class="form-control" maxlength="50">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="requerimiento[preferido]" id="reqPreferido" value="1">
                                <label class="form-check-label" for="reqPreferido">Preferido</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Consumo esperado (kg/día)</label>
                            <input type="number" step="0.0001" name="requerimiento[consumo_esperado_kg_dia]" class="form-control" min="0">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Comentario</label>
                            <input type="text" name="requerimiento[comentario]" class="form-control">
                        </div>

                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-2"><label class="form-label">Humedad %</label><input type="number" name="requerimiento[humedad]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">MS %</label><input type="number" name="requerimiento[materia_seca]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">PC %</label><input type="number" name="requerimiento[proteina_cruda]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">FB %</label><input type="number" name="requerimiento[fibra_bruta]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">EE %</label><input type="number" name="requerimiento[extracto_etereo]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">ELN %</label><input type="number" name="requerimiento[eln]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">Ceniza %</label><input type="number" name="requerimiento[ceniza]" class="form-control" step="0.0001" min="0" max="100"></div>
                                <div class="col-md-2"><label class="form-label">ED (Mcal/kg)</label><input type="number" name="requerimiento[energia_digestible]" class="form-control" step="0.0001" min="0"></div>
                                <div class="col-md-2"><label class="form-label">EM (Mcal/kg)</label><input type="number" name="requerimiento[energia_metabolizable]" class="form-control" step="0.0001" min="0"></div>
                                <div class="col-md-2"><label class="form-label">EN (Mcal/kg)</label><input type="number" name="requerimiento[energia_neta]" class="form-control" step="0.0001" min="0"></div>
                                <div class="col-md-2"><label class="form-label">ED ap. (Mcal/kg)</label><input type="number" name="requerimiento[energia_digestible_ap]" class="form-control" step="0.0001" min="0"></div>
                                <div class="col-md-2"><label class="form-label">EM ap. (Mcal/kg)</label><input type="number" name="requerimiento[energia_metabolizable_ap]" class="form-control" step="0.0001" min="0"></div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="text-uppercase text-muted">Aminoácidos (% proteína)</h6>
                            <hr class="mt-1">
                            <div class="table-responsive" style="max-height: 300px;">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th class="text-center" style="width:140px;">Mín</th>
                                            <th class="text-center" style="width:140px;">Máx</th>
                                            <th class="text-center" style="width:160px;">Recomendado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aminoacidos as $a)
                                            <tr>
                                                <td>
                                                    {{ $a->nombre }}
                                                    @if($a->abreviatura)
                                                        <small class="text-muted">({{ $a->abreviatura }})</small>
                                                    @endif
                                                    @if($a->esencial)
                                                        <span class="badge bg-info ms-1">Esencial</span>
                                                    @endif
                                                </td>
                                                <td><input type="number" step="0.000001" min="0" max="100" name="aminoacidos[{{ $a->id }}][valor_min]" class="form-control form-control-sm text-end"></td>
                                                <td><input type="number" step="0.000001" min="0" max="100" name="aminoacidos[{{ $a->id }}][valor_max]" class="form-control form-control-sm text-end"></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.000001" min="0" max="100" name="aminoacidos[{{ $a->id }}][valor_recomendado]" class="form-control text-end">
                                                        <span class="input-group-text">%</span>
                                                        <input type="hidden" name="aminoacidos[{{ $a->id }}][unidad]" value="%">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success"><i class="material-icons me-2">save</i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>