<div class="modal fade" id="showRolModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info py-3">
                <h5 class="modal-title text-white d-flex align-items-center">
                    <i class="material-icons me-2">visibility</i>
                    <span>Detalle del Rol: <span class="fw-normal">{{ $role->name }}</span></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-0">
                <div class="px-4 pt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-xs text-uppercase font-weight-bolder text-secondary mb-1">Identificador</p>
                            <p class="text-sm font-weight-bold text-dark">ID #{{ $role->id }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="text-xs text-uppercase font-weight-bolder text-secondary mb-1">Fecha de Creación</p>
                            <p class="text-sm font-weight-bold text-dark">{{ $role->created_at->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="nav-wrapper position-relative end-0 mt-2 px-4">
                    <ul class="nav nav-pills nav-fill p-1 bg-gray-100" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#permisos-tab-{{ $role->id }}" role="tab" aria-selected="true">
                                <i class="material-icons text-sm me-1">admin_panel_settings</i> Permisos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#usuarios-tab-{{ $role->id }}" role="tab" aria-selected="false">
                                <i class="material-icons text-sm me-1">group</i> Usuarios asignados
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active p-4" id="permisos-tab-{{ $role->id }}" role="tabpanel">
                        {{-- ⬇⬇⬇ AQUÍ VA EL BLOQUE PHP PARA TRADUCCIONES ⬇⬇⬇ --}}
                        @php
                            // Diccionario de traducciones (igual al de create/edit)
                            $traducciones = [
                               'view dashboard'    => 'Ver panel de control',
                            //Personas
                            'view personas'     => 'Ver persona',
                            'create personas'   => 'Crear persona',
                            'edit personas'     => 'Ediatr persona',
                            'delete personas'   => 'Deshabilitar persona',
                            //Proveedores
                            'view proveedores'   => 'Ver proveedores',
                            'create proveedores' => 'Crear proveedor',
                            'edit proveedores'   => 'Editar proveedor',
                            'delete proveedores' => 'Deshabilitar proveedor',
                            //Materia prima
                            'view materia_prima'   => 'Ver materia prima',
                            'create materia_prima' => 'Crear materia prima',
                            'edit materia_prima'   => 'Editar materia prima',
                            'delete materia_prima' => 'Deshabilitar materia prima',
                            //Repuesto
                            'view repuestos'   => 'Ver repuesto',
                            'create repuestos' => 'Crear repuesto',
                            'edit repuestos'   => 'Editar repuesto',
                            'delete repuestos' => ' Deshabilitar repuesto',
                            //Usuario 
                            'view users'    => 'Ver usuario',
                            'create users'  => 'Crear usuario',
                            'edit users'    => 'Editar usuario',
                            'delete users'  => 'Deshabilitar usuario',
                            'assign roles'  => 'Asignar rol',
                            //Reporte
                            'view reports'     => 'Ver reporte',
                            'generate reports' => 'Generar PDF',
                            //Configuracion 
                            'manage settings'  => 'Configuración del Sistema',
                            //Roles
                            'view roles'       => 'Ver rol',
                            'create roles'     => 'Crear rol',
                            'edit roles'       => 'Editar rol',
                            'delete roles'     => 'Deshabilitar rol'
                            ];

                            // Agrupar permisos por módulo
                            $rolePermissions = $role->permissions->groupBy(function($perm) use ($traducciones) {
                                $parts = explode(' ', $perm->name);
                                $module = count($parts) > 1 ? ucfirst($parts[1]) : 'General';
                                
                                // Traducir el nombre del módulo si existe en el diccionario
                                if (isset($traducciones[$perm->name])) {
                                    // Extraer el módulo de la traducción
                                    $permTraducido = $traducciones[$perm->name];
                                    if (str_contains($permTraducido, ' ')) {
                                        $palabras = explode(' ', $permTraducido);
                                        if (count($palabras) > 1) {
                                            return $palabras[count($palabras) - 1]; // Última palabra como módulo
                                        }
                                    }
                                }
                                
                                // Mapear nombres de módulos
                                $moduleNames = [
                                    'Dashboard' => 'Dashboard',
                                    'Personas' => 'Personas',
                                    'Proveedores' => 'Proveedores',
                                    'Materia_prima' => 'Materia Prima',
                                    'Repuestos' => 'Repuestos',
                                    'Users' => 'Usuarios',
                                    'Reports' => 'Reportes',
                                    'Settings' => 'Configuración',
                                    'Roles' => 'Roles',
                                    'Bitacora' => 'Bitácora'
                                ];
                                
                                return $moduleNames[$module] ?? $module;
                            });
                        @endphp

                        <div class="row">
                            @forelse($rolePermissions as $modulo => $perms)
                                <div class="col-md-6 mb-3">
                                    <div class="card shadow-none border border-light">
                                        <div class="card-header p-2 bg-light">
                                            <h6 class="text-xxs text-uppercase font-weight-bolder text-info mb-0">{{ $modulo }}</h6>
                                            <small class="text-muted">({{ $perms->count() }})</small>
                                        </div>
                                        <div class="card-body p-2">
                                            @foreach($perms as $p)
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="material-icons text-success text-xxs me-2">check_circle</i>
                                                    <span class="text-xs text-dark">
                                                        {{ $traducciones[$p->name] ?? ucwords(str_replace(['_', '-'], ' ', $p->name)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <p class="text-sm text-secondary">Este rol no tiene permisos asignados.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="tab-pane fade p-4" id="usuarios-tab-{{ $role->id }}" role="tabpanel">
                        <div class="table-responsive p-0" style="max-height: 350px;">
                            <table class="table align-items-center mb-0">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Nombre del usuario</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Correo electronico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($role->users as $user)
                                        <tr>
                                            <td class="ps-3">
                                                <h6 class="mb-0 text-xs text-dark">{{ $user->name }}</h6>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 text-secondary">{{ $user->email }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-5">
                                                <p class="text-sm text-secondary">No hay usuarios vinculados a este rol.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4">
                <button type="button" class="btn btn-secondary mb-0" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>