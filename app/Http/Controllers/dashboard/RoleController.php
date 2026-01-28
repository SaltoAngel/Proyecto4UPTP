<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Models\Role;
use App\Models\Bitacora;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view roles', ['only' => ['index', 'show']]);
        $this->middleware('permission:create roles', ['only' => ['store']]);
        $this->middleware('permission:edit roles', ['only' => ['update']]);
        $this->middleware('permission:delete roles', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $search = $request->input('search');

        $roles = Role::withTrashed()
            ->with(['permissions', 'users'])
            ->withCount(['permissions', 'users'])
            ->where('name', '!=', 'superadmin')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderByRaw("CASE WHEN name = 'administrador' THEN 1 ELSE 2 END ASC")
            ->paginate(10)
            ->appends(['search' => $search]);

        // 1. Obtenemos todos los permisos
        $all_permissions = Permission::orderBy('name')->get();

        // 2. Los agrupamos usando tu función privada groupPermissions
        $permissions = $this->groupPermissions($all_permissions);

        // 3. Pasamos la variable al compact
        return view('dashboard.roles.index', compact('roles', 'search', 'permissions'));
    }

    //Para crear los roles y sincronizarlos con los permisos
    public function store(RoleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // FILTRAR PERMISOS - EXCLUIR PERMISOS DE GESTIÓN DE ROLES
            $filteredPermissions = $this->filterRolePermissions($request->permissions);

            // Validar que haya al menos un permiso después del filtrado
            if (empty($filteredPermissions)) {
                return back()->with('error', 'Debe seleccionar al menos un permiso válido.')->withInput();
            }

            // 1. Crear el rol (El RoleRequest debe validar 'name' y 'permissions')
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            // 2. Sincronizar permisos filtrados
            $role->permissions()->sync($filteredPermissions);

            // 3. Registro en Bitácora (Manteniendo tu formato)
            Bitacora::registrar(
                'Roles',
                'Creación',
                "Se creó el rol [{$role->name}] con los permisos seleccionados.",
                null,
                ['permisos_ids' => $filteredPermissions]
            );

            DB::commit();

            // Limpiar caché de Spatie
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('dashboard.roles.index')->with('success', 'Rol creado exitosamente con sus permisos.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el rol: ' . $e->getMessage())->withInput();
        }
    }

    //Para editar los roles y permisos
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->isProtectedRole()) {
            return redirect()->route('dashboard.roles.index')->with('info', 'Rol protegido.');
        }

        try {
            DB::beginTransaction();
            
            // FILTRAR PERMISOS - EXCLUIR PERMISOS DE GESTIÓN DE ROLES
            $filteredPermissions = $this->filterRolePermissions($request->permissions);

            // Validar que haya al menos un permiso después del filtrado
            if (empty($filteredPermissions)) {
                return back()->with('error', 'Debe seleccionar al menos un permiso válido.')->withInput();
            }

            // Actualiza nombre y permisos filtrados
            $role->update(['name' => $request->name]);
            $role->permissions()->sync($filteredPermissions);

            Bitacora::registrar('Roles', 'Edición', "Se actualizó el rol [{$role->name}] y sus permisos.");
            DB::commit();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('dashboard.roles.index')->with('success', 'Rol actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Para ver los detalles de cada registro de rol
    public function show(Role $role): View
    {
        // Cargamos las relaciones necesarias para el detalle
        $role->load(['permissions', 'users']);

        // Obtenemos todos los permisos agrupados
        $all_permissions = Permission::orderBy('name')->get();
        $permissions = $this->groupPermissions($all_permissions);

        return view('dashboard.roles.modals.show', compact('role', 'permissions'));
    }

    // Para deshabilitar
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->isProtectedRole()) {
            return back()->with('error', 'No se puede deshabilitar un rol protegido.');
        }

        $nombre = $role->name;
        $role->delete();

        Bitacora::registrar('Roles', 'Deshabilitación', "Se desactivó el rol [$nombre].");

        return redirect()->route('dashboard.roles.index')->with('success', 'Rol desactivado correctamente.');
    }

    // Para volver a habilitar
    public function restore($id): RedirectResponse
    {
        // Buscamos el rol incluso entre los que tienen 'deleted_at'
        $role = Role::withTrashed()->findOrFail($id);

        $role->restore();

        Bitacora::registrar('Roles', 'Restauración', "Se habilitó nuevamente el rol [{$role->name}].");

        return redirect()->route('dashboard.roles.index')->with('success', 'Rol activado nuevamente.');
    }

    private function groupPermissions($permissions)
    {
        return $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = count($parts) > 1 ? $parts[1] : 'general';
            $moduleNames = [
                'dashboard' => 'Dashboard', 'proveedores' => 'Proveedores', 'personas' => 'Personas',
                'bitacora' => 'Bitácora', 'reportes' => 'Reportes', 'users' => 'Usuarios',
                'roles' => 'Roles', 'settings' => 'Configuración', 'materia_prima' => 'Materia Prima',
                'repuestos' => 'Repuestos',
            ];
            return $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module));
        });
    }

    /**
     * FILTRA PERMISOS PARA EXCLUIR PERMISOS DE GESTIÓN DE ROLES
     * Solo el rol 'administrador' puede tener estos permisos
     */
    private function filterRolePermissions(array $permissionIds): array
    {
        // Obtener los IDs de los permisos de gestión de roles
        $roleManagementPermissions = Permission::where('name', 'like', '%roles%')
            ->pluck('id')
            ->toArray();

        // Filtrar para excluir permisos de gestión de roles
        return array_filter($permissionIds, function($permissionId) use ($roleManagementPermissions) {
            return !in_array($permissionId, $roleManagementPermissions);
        });
    }
}