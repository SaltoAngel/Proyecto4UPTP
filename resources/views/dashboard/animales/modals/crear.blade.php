<div class="modal fade" id="crearAnimalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Animal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCrearAnimal" action="{{ route('dashboard.animales.store') }}" method="POST">
                @csrf
                
                <!-- Navegación simple -->
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="d-flex mb-4 border-bottom pb-2 flex-wrap gap-1">
                        <button type="button" class="btn btn-outline-primary btn-sm active" data-section="datosBasicos">
                            <i class="bi bi-info-circle me-1"></i>Básicos
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-section="tolerancia">
                            <i class="bi bi-egg-fried me-1"></i>Tolerancia
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-section="requerimientos">
                            <i class="bi bi-nutrition me-1"></i>Requerimientos
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-section="aminoacidos">
                            <i class="bi bi-dna me-1"></i>Aminoácidos
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-section="minerales">
                            <i class="bi bi-gem me-1"></i>Minerales
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-section="vitaminas">
                            <i class="bi bi-capsule me-1"></i>Vitaminas
                        </button>
                    </div>

                    <!-- Sección 1: Datos Básicos -->
                    <div id="datosBasicos" class="form-section">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-card-checklist me-2"></i>Datos Básicos del Animal
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Especie <span class="text-danger">*</span></label>
                                    <select name="especie_id" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($especies as $e)
                                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nombre común/tipo <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" maxlength="100" required 
                                           placeholder="Ej: Cerdo Yorkshire acabado">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Raza o línea</label>
                                    <input type="text" name="raza_linea" class="form-control" maxlength="100" 
                                           placeholder="Ej: Yorkshire, Holstein, Ross 308">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Etapa</label>
                                    <input type="text" name="etapa_especifica" class="form-control mt-1" 
                                           placeholder="Especificación (ej: Acabado a T.20 C)">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Producto final</label>
                                    <select name="producto_final" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <option value="leche">Leche</option>
                                        <option value="carne">Carne</option>
                                        <option value="huevos">Huevos</option>
                                        <option value="doble_proposito">Doble Propósito</option>
                                        <option value="reproduccion">Reproducción</option>
                                        <option value="trabajo">Trabajo</option>
                                        <option value="lana">Lana/Fibra</option>
                                        <option value="miel">Miel</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Sistema de producción</label>
                                    <select name="sistema_produccion" class="form-select">
                                        <option value="intensivo">Intensivo</option>
                                        <option value="semi-intensivo">Semi-intensivo</option>
                                        <option value="extensivo">Extensivo</option>
                                        <option value="organico">Orgánico</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Edad (semanas)</label>
                                    <div class="input-group">
                                        <input type="number" name="edad_semanas" class="form-control" min="0" 
                                               placeholder="18" onchange="calcularDias()">
                                        <span class="input-group-text">semanas</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Peso vivo inicial (kg)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="peso_minimo_kg" class="form-control" min="0" 
                                               placeholder="60.00">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>                          
                                <div class="col-md-6">
                                    <label class="form-label">Consumo esperado (kg/día)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.001" name="requerimiento[consumo_esperado_kg_dia]" 
                                               class="form-control" min="0" placeholder="2.500">
                                        <span class="input-group-text">kg/día</span>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="2" 
                                              placeholder="Describa características relevantes, notas de manejo, observaciones..."></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="activo" id="activoAnimal" checked value="1">
                                        <label class="form-check-label" for="activoAnimal">
                                            <i class="bi bi-toggle-on me-1"></i>Activo en el sistema
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Tolerancia a Alimentos -->
                    <div id="tolerancia" class="form-section" style="display: none;">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-egg-fried me-2"></i>Tolerancia a Tipos de Alimentos
                            </h6>
                            <p class="text-muted mb-3">Porcentaje máximo recomendado en la dieta diaria</p>
                            
                            <div class="row g-3">
                                @php
                                    $tiposAlimentos = [
                                        'energeticos' => ['nombre' => 'Energéticos', 'color' => 'warning'],
                                        'proteicos' => ['nombre' => 'Proteicos', 'color' => 'danger'],
                                        'forrajes_verdes' => ['nombre' => 'Forrajes Verdes', 'color' => 'success'],
                                        'forrajes_secos' => ['nombre' => 'Forrajes Secos', 'color' => 'secondary'],
                                        'ensilajes' => ['nombre' => 'Ensilajes', 'color' => 'info'],
                                        'minerales' => ['nombre' => 'Minerales', 'color' => 'primary'],
                                        'vitaminas' => ['nombre' => 'Vitaminas', 'color' => 'purple'],
                                        'aditivos' => ['nombre' => 'Aditivos', 'color' => 'dark']
                                    ];
                                @endphp
                                
                                @foreach($tiposAlimentos as $key => $tipo)
                                <div class="col-md-3 col-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-{{ $tipo['color'] }} bg-opacity-10 p-2 rounded me-2">
                                                    <i class="bi bi-circle-fill text-{{ $tipo['color'] }}"></i>
                                                </div>
                                                <h6 class="card-title mb-0">{{ $tipo['nombre'] }}</h6>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input type="number" name="tolerancia_alimentos[{{ $key }}][porcentaje_maximo]" 
                                                           class="form-control text-end porcentaje-input" 
                                                           min="0" max="100" step="0.1" value="0">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $tipo['color'] }}" 
                                                     id="progress-{{ $key }}" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Nota:</strong> Los porcentajes representan el máximo por categoría, no necesariamente suman 100%
                            </div>
                        </div>
                    </div>

                    <!-- Sección 3: Requerimientos Nutricionales -->
                    <div id="requerimientos" class="form-section" style="display: none;">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-nutrition me-2"></i>Requerimientos Nutricionales Diarios
                            </h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fuente de los datos</label>
                                    <input type="text" name="requerimiento[fuente]" class="form-control" 
                                           placeholder="Ej: NRC 2012, INRA 2018, Empírico">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Descripción del requerimiento</label>
                                    <input type="text" name="requerimiento[descripcion]" class="form-control" 
                                           placeholder="Ej: Base estándar, Máximo rendimiento">
                                </div>
                            </div>
                            
                            <!-- WEENDE -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-bar-chart me-2"></i>WEENDE - Análisis Proximal (g/día)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach($weendeNutrientes as $key => $nombre)
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small fw-semibold">{{ $nombre }}</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" 
                                                       name="requerimientos_diarios[weende][{{ $key }}]" 
                                                       class="form-control" placeholder="0.00">
                                                <span class="input-group-text">g</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Minerales -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-gem me-2"></i>Minerales
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Macrominerales (%)</h6>
                                            <div class="row g-3">
                                                @foreach($macrominerales as $key => $nombre)
                                                <div class="col-md-6">
                                                    <label class="form-label small">{{ $nombre }}</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.0001" 
                                                               name="requerimientos_diarios[macrominerales_porcentaje][{{ $key }}]" 
                                                               class="form-control" placeholder="0.0000">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Microminerales (mg/día)</h6>
                                            <div class="row g-3">
                                                @foreach($microminerales as $key => $nombre)
                                                <div class="col-md-6">
                                                    <label class="form-label small">{{ $nombre }}</label>
                                                    <input type="number" step="0.001" 
                                                           name="requerimientos_diarios[microminerales][{{ $key }}]" 
                                                           class="form-control" placeholder="0.000">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Energía -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-lightning-charge me-2"></i>Energía (Kcal/día)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach($energias as $key => $nombre)
                                        <div class="col-md-4 col-6">
                                            <label class="form-label small">{{ $nombre }}</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" 
                                                       name="requerimientos_diarios[energia][{{ $key }}]" 
                                                       class="form-control" placeholder="0.00">
                                                <span class="input-group-text">Kcal</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 4: Aminoácidos -->
                    <div id="aminoacidos" class="form-section" style="display: none;">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-dna me-2"></i>Aminoácidos (% de proteína)
                            </h6>
                            
                            <div class="row g-3">
                                @foreach($aminoacidos as $a)
                                <div class="col-md-3 col-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                                                    <i class="bi bi-dna text-info"></i>
                                                </div>
                                                <div>
                                                    <h6 class="card-title mb-0">{{ $a->nombre }}</h6>
                                                    @if($a->abreviatura)
                                                        <small class="text-muted">({{ $a->abreviatura }})</small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">Valor</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.000001" 
                                                           name="aminoacidos[{{ $a->id }}][valor]" 
                                                           class="form-control text-end" placeholder="0.000000">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sección 5: Minerales -->
                    <div id="minerales" class="form-section" style="display: none;">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-gem me-2"></i>Minerales
                            </h6>
                            
                            <div class="row g-3">
                                @foreach($minerales as $m)
                                <div class="col-md-3 col-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                                                    <i class="bi bi-gem text-warning"></i>
                                                </div>
                                                <div>
                                                    <h6 class="card-title mb-0">{{ $m->nombre }}</h6>
                                                    @if($m->simbolo)
                                                        <small class="text-muted">({{ $m->simbolo }})</small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">Valor</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.000001" 
                                                           name="minerales[{{ $m->id }}][valor]" 
                                                           class="form-control text-end" placeholder="0.000000">
                                                    <span class="input-group-text">{{ $m->unidad ?? 'mg/kg' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sección 6: Vitaminas -->
                    <div id="vitaminas" class="form-section" style="display: none;">
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-capsule me-2"></i>Vitaminas
                            </h6>
                            
                            <div class="row g-3">
                                @foreach($vitaminas as $v)
                                <div class="col-md-3 col-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 p-2 rounded me-2">
                                                    <i class="bi bi-capsule text-success"></i>
                                                </div>
                                                <div>
                                                    <h6 class="card-title mb-0">{{ $v->nombre }}</h6>
                                                    @if($v->tipo)
                                                        <small class="text-muted">({{ $v->tipo }})</small>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">Valor</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.000001" 
                                                           name="vitaminas[{{ $v->id }}][valor]" 
                                                           class="form-control text-end" placeholder="0.000000">
                                                    <span class="input-group-text">{{ $v->unidad ?? 'UI/kg' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnPrev" style="display: none;">
                        <i class="bi bi-chevron-left me-1"></i>Anterior
                    </button>
                    <button type="button" class="btn btn-primary" id="btnNext">
                        <i class="bi bi-chevron-right me-1"></i>Siguiente
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                        <i class="bi bi-check-circle me-1"></i>Guardar Animal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sections = ['datosBasicos', 'tolerancia', 'requerimientos', 'aminoacidos', 'minerales', 'vitaminas'];
    let currentSection = 0;
    
    // Botones de navegación
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');
    
    // Navegación por botones superiores
    document.querySelectorAll('[data-section]').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetSection = this.getAttribute('data-section');
            goToSection(sections.indexOf(targetSection));
        });
    });
    
    // Botón siguiente
    btnNext.addEventListener('click', function() {
        if (validateCurrentSection()) {
            if (currentSection < sections.length - 1) {
                goToSection(currentSection + 1);
            } else {
                // Última sección - mostrar botón de enviar
                btnSubmit.style.display = 'inline-block';
                btnNext.style.display = 'none';
            }
        }
    });
    
    // Botón anterior
    btnPrev.addEventListener('click', function() {
        if (currentSection > 0) {
            goToSection(currentSection - 1);
        }
    });
    
    function goToSection(index) {
        // Ocultar sección actual
        document.getElementById(sections[currentSection]).style.display = 'none';
        document.querySelector(`[data-section="${sections[currentSection]}"]`).classList.remove('active', 'btn-primary');
        document.querySelector(`[data-section="${sections[currentSection]}"]`).classList.add('btn-outline-secondary');
        
        // Mostrar nueva sección
        currentSection = index;
        document.getElementById(sections[currentSection]).style.display = 'block';
        document.querySelector(`[data-section="${sections[currentSection]}"]`).classList.add('active', 'btn-primary');
        document.querySelector(`[data-section="${sections[currentSection]}"]`).classList.remove('btn-outline-secondary');
        
        // Actualizar botones
        btnPrev.style.display = currentSection > 0 ? 'inline-block' : 'none';
        btnNext.style.display = currentSection < sections.length - 1 ? 'inline-block' : 'none';
        btnSubmit.style.display = 'none';
        
        // Scroll al inicio de la sección
        document.getElementById(sections[currentSection]).scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    function validateCurrentSection() {
        const sectionId = sections[currentSection];
        const section = document.getElementById(sectionId);
        
        // Solo validamos campos requeridos (Básicos)
        if (sectionId === 'datosBasicos') {
            const requiredInputs = section.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                alert('Por favor complete los campos requeridos antes de continuar.');
                return false;
            }
        }
        
        return true;
    }
    
    // Barra de progreso para tolerancia
    document.querySelectorAll('.porcentaje-input').forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value) || 0;
            const key = this.name.match(/\[(.*?)\]\[/)[1];
            const progressBar = document.getElementById(`progress-${key}`);
            if (progressBar) {
                progressBar.style.width = `${Math.min(value, 100)}%`;
                progressBar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                if (value > 80) {
                    progressBar.classList.add('bg-danger');
                } else if (value > 50) {
                    progressBar.classList.add('bg-warning');
                } else {
                    progressBar.classList.add('bg-success');
                }
            }
        });
    });
    
    // Validación en tiempo real para campos numéricos
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('blur', function() {
            const min = parseFloat(this.getAttribute('min'));
            const max = parseFloat(this.getAttribute('max'));
            const value = parseFloat(this.value);
            
            if (!isNaN(value)) {
                if (min !== null && value < min) {
                    this.value = min;
                }
                if (max !== null && value > max) {
                    this.value = max;
                }
            }
        });
    });
    
    // Función para calcular días desde semanas
    window.calcularDias = function() {
        const semanasInput = document.querySelector('input[name="edad_semanas"]');
        const diasMinInput = document.querySelector('input[name="edad_minima_dias"]');
        const diasMaxInput = document.querySelector('input[name="edad_maxima_dias"]');
        
        if (semanasInput && diasMinInput) {
            const semanas = parseFloat(semanasInput.value) || 0;
            const dias = semanas * 7;
            diasMinInput.value = dias;
            
            // Si no hay valor en edad máxima, poner +2 semanas
            if (diasMaxInput && !diasMaxInput.value) {
                diasMaxInput.value = dias + 14;
            }
        }
    };
    
    // Auto-calcular al cargar si hay valor en semanas
    const semanasInput = document.querySelector('input[name="edad_semanas"]');
    if (semanasInput && semanasInput.value) {
        calcularDias();
    }
    
    // Inicializar primera sección
    goToSection(0);
});
</script>

<style>
.form-section {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Estilos estandarizados para todas las tarjetas */
.card {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    transition: all 0.2s;
    height: 100%;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
}

.card-body {
    padding: 1rem;
}

.card-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
}

/* Input groups estandarizados */
.input-group {
    margin-bottom: 0.5rem;
}

.input-group .form-control {
    border-right: 0;
}

.input-group-text {
    background-color: #f8f9fa;
    border-left: 0;
    font-size: 0.875rem;
    min-width: 50px;
    justify-content: center;
}

/* Labels pequeños consistentes */
.form-label.small {
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: #6c757d;
    display: block;
}

/* Progress bars para tolerancia */
.progress {
    background-color: #e9ecef;
    height: 6px;
    border-radius: 3px;
    margin-top: 0.25rem;
}

/* Iconos con fondo */
.bg-opacity-10 {
    opacity: 0.1;
}

/* Botones de navegación */
[data-section] {
    min-width: 110px;
    margin-bottom: 0.25rem;
}

.btn-outline-secondary.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

/* Grid responsivo consistente */
.row.g-3 > div {
    margin-bottom: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-3.col-6, .col-md-4.col-6, .col-md-6 {
        width: 50%;
    }
    
    [data-section] {
        min-width: 90px;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .input-group-text {
        min-width: 40px;
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .col-md-3.col-6, .col-md-4.col-6, .col-md-6 {
        width: 100%;
    }
    
    .modal-footer .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}

/* Estilos para campos readonly */
input[readonly] {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

/* Mejora para selects */
.form-select {
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

/* Espaciado entre grupos */
.mb-4 {
    margin-bottom: 1.5rem !important;
}
</style>