<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Models\Role;
use App\Models\Bitacora; //
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
        $this->middleware('permission:create roles', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit roles', ['only' => ['edit', 'update', 'assignPermissions', 'updatePermissions']]);
        $this->middleware('permission:delete roles', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $roles = Role::withTrashed() // Para ver deshabilitados
            ->withCount(['permissions', 'users'])
            ->where('name', '!=', 'superadmin') // [REQUERIMIENTO] Superadmin invisible
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderByRaw("CASE WHEN name = 'administrador' THEN 1 ELSE 2 END ASC")
            ->paginate(10);

        return view('dashboard.roles.index', compact('roles', 'search'));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        // [REQUERIMIENTO] Guardado diferido en sesión
        session(['temp_role_name' => $request->name]);
        return redirect()->route('dashboard.roles.assign-permissions', 0)
            ->with('info', 'Asigne permisos para finalizar la creación del rol.');
    }

    public function edit(Role $role): View
    {
        if ($role->isProtectedRole()) {
            abort(403, 'No se puede editar este rol del sistema.');
        }
        return view('dashboard.roles.modals.edit', compact('role'));
    }

    public function assignPermissions($id): View
    {
        if ($id == 0) {
            $role = new Role(['name' => session('temp_role_name')]);
            $rolePermissions = [];
        } else {
            $role = Role::withTrashed()->findOrFail($id);
            $rolePermissions = $role->permissions->pluck('id')->toArray();
        }

        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissions($permissions);

        return view('dashboard.roles.modals.asignar_permiso', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    public function updatePermissions(RolePermissionRequest $request, $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($id == 0) {
                // Se crea en BD solo tras asignar permisos
                $role = Role::create([
                    'name' => session('temp_role_name'),
                    'guard_name' => 'web'
                ]);
                $accion = 'Creación';
                session()->forget('temp_role_name');
            } else {
                $role = Role::withTrashed()->findOrFail($id);
                if ($role->isProtectedRole()) return back()->with('error', 'Rol protegido.');
                $accion = 'Edición de Permisos';
            }

            // [SOLUCIÓN ERROR] Sincronización por ID
            $role->permissions()->sync($request->permissions);

            // [BITÁCORA] Registro de acción
            Bitacora::registrar(
                'Roles',
                $accion,
                "Se procesó el rol [{$role->name}] con los permisos seleccionados.",
                null,
                ['permisos_ids' => $request->permissions]
            );

            DB::commit();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('dashboard.roles.index')->with('success', 'Operación exitosa.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Para deshabilitar (Ya lo tienes, pero asegúrate de que use Bitacora)
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->isProtectedRole()) {
            return back()->with('error', 'No se puede deshabilitar un rol protegido.');
        }

        $nombre = $role->name;
        $role->delete(); // Esto llena 'deleted_at', no borra la fila.

        \App\Models\Bitacora::registrar('Roles', 'Deshabilitación', "Se desactivó el rol [$nombre].");

        return redirect()->route('dashboard.roles.index')->with('success', 'Rol desactivado correctamente.');
    }

    // NUEVO: Para volver a habilitar
    public function restore($id): RedirectResponse
    {
        // Buscamos el rol incluso entre los que tienen 'deleted_at'
        $role = Role::withTrashed()->findOrFail($id);

        $role->restore(); // Esto pone 'deleted_at' en NULL (lo activa)

        \App\Models\Bitacora::registrar('Roles', 'Restauración', "Se habilitó nuevamente el rol [{$role->name}].");

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
}