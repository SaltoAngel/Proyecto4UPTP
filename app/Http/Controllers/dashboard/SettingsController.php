<?php
/**
 * Controlador: SettingsController
 * Propósito: Mostrar y actualizar la configuración del usuario autenticado.
 * Flujo:
 *  - index(): muestra la vista con datos de usuario y su persona relacionada
 *  - update(): valida y guarda campos en persona; si se envía password, valida current_password y actualiza en users
 * Consideraciones:
 *  - Email se almacena en la tabla personas (no en users)
 *  - Usa mensajes de error/éxito vía sesión
 */

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $persona = $user->persona;
        return view('material.settings', compact('user', 'persona'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        $validated = $request->validate([
            // Persona
            'nombres' => ['nullable','string','max:120'],
            'apellidos' => ['nullable','string','max:120'],
            'telefono' => ['nullable','string','max:50'],
            'telefono_alternativo' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:190'],
            'direccion' => ['nullable','string','max:255'],
            'estado' => ['nullable','string','max:120'],
            'ciudad' => ['nullable','string','max:120'],
            // Password
            'current_password' => ['nullable','string','min:6'],
            'password' => ['nullable','string','min:6','confirmed'],
        ]);

        // Actualizar persona
        if ($persona) {
            $persona->fill(collect($validated)->only([
                'nombres','apellidos','telefono','telefono_alternativo','email','direccion','estado','ciudad'
            ])->toArray());
            $persona->save();
        }

        // Cambio de contraseña si se envió
        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.'])->withInput();
            }
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        return back()->with('status', 'Configuración actualizada correctamente');
    }
}
