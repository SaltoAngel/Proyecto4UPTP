<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Models\Role;
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
        
        $roles = Role::withCount(['permissions', 'users'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderByRaw("FIELD(name, 'superadmin', 'supervisor', 'coordinador', 'nutricionista') DESC")
            ->orderBy('name')
            ->paginate(10);

        return view('dashboard.roles.index', compact('roles', 'search'));
    }

    public function create(): View
    {
        return view('dashboard.roles.create');
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            // Redirigir a la modal de permisos
            return redirect()->route('dashboard.roles.assign-permissions', $role)
                ->with('success', 'Rol creado. Ahora asigne los permisos.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Role $role): View
    {
        $role->load(['permissions', 'users' => function ($query) {
            $query->latest()->take(5);
        }]);

        $permissionsByModule = $role->getPermissionsByModule();

        return view('dashboard.roles.show', compact('role', 'permissionsByModule'));
    }

    public function edit(Role $role): View
    {
        if ($role->isProtectedRole()) {
            abort(403, 'No se puede editar este rol del sistema.');
        }

        return view('dashboard.roles.edit', compact('role'));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->isProtectedRole()) {
            return back()->with('error', 'No se puede editar este rol del sistema.');
        }

        try {
            $role->update([
                'name' => $request->name
            ]);

            return redirect()->route('dashboard.roles.index')
                ->with('success', 'Rol actualizado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el rol: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar modal para asignar permisos (después de crear o editar)
     */
    public function assignPermissions(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('dashboard.roles.assign-permissions', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Actualizar permisos del rol (desde la modal)
     */
    public function updatePermissions(RolePermissionRequest $request, Role $role): RedirectResponse
    {
        if ($role->isProtectedRole()) {
            return back()->with('error', 'No se puede editar permisos de este rol del sistema.');
        }

        try {
            $role->syncPermissions($request->permissions);

            return redirect()->route('dashboard.roles.index')
                ->with('success', 'Permisos asignados exitosamente al rol ' . $role->name);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al asignar permisos: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->isProtectedRole()) {
            return back()->with('error', 'No se puede eliminar este rol del sistema.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        try {
            $role->delete();

            return redirect()->route('dashboard.roles.index')
                ->with('success', 'Rol eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Agrupar permisos por módulo para la modal.
     */
    private function groupPermissions($permissions)
    {
        return $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            $module = count($parts) > 1 ? $parts[1] : 'general';
            
            $moduleNames = [
                'dashboard' => 'Dashboard',
                'proveedores' => 'Proveedores',
                'personas' => 'Personas',
                'bitacora' => 'Bitácora',
                'reportes' => 'Reportes',
                'users' => 'Usuarios',
                'roles' => 'Roles',
                'settings' => 'Configuración',
                'materia_prima' => 'Materia Prima',
                'repuestos' => 'Repuestos',
            ];

            return $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module));
        });
    }
}
