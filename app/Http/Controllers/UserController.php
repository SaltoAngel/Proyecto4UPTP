<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Personas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserVerificationMail;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $estado = $request->input('estado', '');
        $rol = $request->input('rol', '');

        $users = User::with(['persona', 'roles'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                      ->orWhereHas('persona', function ($q2) use ($search) {
                          $q2->where('nombres', 'like', "%{$search}%")
                             ->orWhere('apellidos', 'like', "%{$search}%")
                             ->orWhere('documento', 'like', "%{$search}%");
                      });
                });
            })
            ->when($estado, function ($query, $estado) {
                return $query->where('status', $estado);
            })
            ->when($rol, function ($query, $rol) {
                return $query->whereHas('roles', function ($q) use ($rol) {
                    $q->where('name', $rol); // Filtrar por nombre del rol
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Pasar roles para el filtro
        $roles = Role::all();

        return view('users.user', compact('users', 'roles'));
    }

    public function create()
    {
        $personas = Personas::whereDoesntHave('user')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get()
            ->map(function ($persona) {
                return [
                    'id' => $persona->id,
                    'text' => $persona->nombre_completo . ' - ' . $persona->documento . ' (' . $persona->email . ')',
                    'email' => $persona->email,
                    'documento' => $persona->documento,
                ];
            })
            ->values()
            ->toArray();

        $roles = Role::all();

        return view('users.create', compact('personas', 'roles'));
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'persona_id' => 'required|exists:personas,id'
        ]);

        try {
            // Verificar que el email pertenece a la persona
            $persona = Personas::findOrFail($request->persona_id);
            
            if ($persona->email !== $request->email) {
                return response()->json([
                    'success' => false,
                    'error' => 'El email no corresponde a la persona seleccionada.'
                ], 422);
            }

            // Verificar que no exista un usuario con este email
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ya existe un usuario con este email.'
                ], 422);
            }

            // Generar código de 8 caracteres alfanuméricos
            $code = Str::upper(Str::random(8));
            
            // Guardar código en cache (válido por 30 minutos)
            Cache::put('verification_code_' . $request->email, $code, now()->addMinutes(30));
            
            // Guardar persona_id asociada al código
            Cache::put('verification_persona_' . $request->email, $request->persona_id, now()->addMinutes(30));
            
            // Guardar email verificado con persona_id para mayor seguridad
            Cache::put('email_verified_' . $request->email . '_' . $request->persona_id, false, now()->addMinutes(30));

            // Enviar email con el código
            Mail::to($request->email)->send(new UserVerificationMail($code));
            
            return response()->json([
                'success' => true,
                'message' => 'Código de verificación enviado al correo de la persona.',
                'code' => $code // Solo para desarrollo, quitar en producción
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al enviar código de verificación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al enviar el código. Por favor intente nuevamente.'
            ], 500);
        }
    }

    public function validateVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|size:8',
            'persona_id' => 'required|exists:personas,id'
        ]);
        
        $storedCode = Cache::get('verification_code_' . $request->email);
        $storedPersonaId = Cache::get('verification_persona_' . $request->email);
        
        if (!$storedCode || $storedCode !== $request->code) {
            return response()->json([
                'success' => false,
                'error' => 'Código de verificación inválido o expirado.'
            ], 422);
        }
        
        if ($storedPersonaId != $request->persona_id) {
            return response()->json([
                'success' => false,
                'error' => 'El código no corresponde a esta persona.'
            ], 422);
        }
        
        // Marcar email como verificado en cache
        Cache::put('email_verified_' . $request->email . '_' . $request->persona_id, true, now()->addMinutes(30));
        
        return response()->json([
            'success' => true,
            'message' => 'Código validado correctamente. Puede continuar con la creación del usuario.',
            'verified' => true
        ]);
    }

    // NUEVO MÉTODO: Verificar estado de verificación
    public function checkVerificationStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'persona_id' => 'required|exists:personas,id'
        ]);

        $isVerified = Cache::get('email_verified_' . $request->email . '_' . $request->persona_id, false);
        
        return response()->json([
            'success' => true,
            'verified' => $isVerified,
            'message' => $isVerified ? 'Código validado correctamente.' : 'Código no validado.'
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'persona_id' => 'required|exists:personas,id',
        'email' => 'required|email|unique:users,email',
        'password' => [
            'required',
            'confirmed',
            'string',
            'min:1'
        ],
        'role_name' => 'required|exists:roles,name', // <-- Añadir validación para role_name
        'verification_code' => 'required|size:8',
    ]);

    try {
        DB::beginTransaction();

        $persona = Personas::findOrFail($request->persona_id);

        if ($persona->email !== $request->email) {
            return back()->withErrors(['email' => 'El email no coincide.'])->withInput();
        }

        // Verificar que el código fue validado
        if (!Cache::get('email_verified_' . $request->email . '_' . $request->persona_id)) {
            return back()->withErrors(['verification_code' => 'Debe validar el código primero.'])->withInput();
        }

        // Verificar el código
        $storedCode = Cache::get('verification_code_' . $request->email);
        $storedPersonaId = Cache::get('verification_persona_' . $request->email);
        
        if (!$storedCode || $storedCode !== $request->verification_code) {
            return back()->withErrors(['verification_code' => 'Código inválido.'])->withInput();
        }

        if ($storedPersonaId != $request->persona_id) {
            return back()->withErrors(['verification_code' => 'El código no corresponde a esta persona.'])->withInput();
        }

        // IMPORTANTE: La contraseña debe ser la cédula (documento)
        $password = $persona->documento;

        // Crear nuevo usuario
        $user = User::create([
            'email' => $persona->email,
            'password' => Hash::make($password),
            'persona_id' => $persona->id,
            'status' => 'pendiente',
        ]);

        // **CORREGIDO: Asignar rol por NOMBRE (Spatie) usando role_name**
        if ($request->has('role_name') && $request->role_name) {
            $user->assignRole($request->role_name); // <-- Usar assignRole con el nombre del rol
        }

        // Limpiar caché
        Cache::forget('verification_code_' . $request->email);
        Cache::forget('verification_persona_' . $request->email);
        Cache::forget('email_verified_' . $request->email . '_' . $request->persona_id);

        DB::commit();

        return redirect()->route('users.user')->with('success', 'Usuario creado exitosamente. La contraseña inicial es la cédula de la persona.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al crear usuario: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
    }
}

    public function show($id)
    {
        $user = User::with(['persona'])->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with(['persona'])->findOrFail($id);
        $roles = Role::all();
        
        return view('users.edit', compact('user', 'roles'));
    }

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validar solo el rol (ya no el estado)
    $request->validate([
        'role' => 'required|exists:roles,name', // Solo validar rol
        // 'status' => 'required|in:pendiente,activo,inactivo', // <-- Eliminado
    ]);

    // NO actualizar el estado aquí
    // $user->update(['status' => $request->status]); // <-- Eliminado

    // Sincronizar rol por NOMBRE (Spatie)
    $user->syncRoles([$request->role]);

    return redirect()->route('users.user')->with('success', 'Usuario actualizado exitosamente.');
}

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.user')->with('success', 'Usuario eliminado exitosamente.');
    }
    
    // NUEVO MÉTODO: Reenviar código
    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'persona_id' => 'required|exists:personas,id'
        ]);

        try {
            // Verificar que existe un proceso de verificación
            $storedPersonaId = Cache::get('verification_persona_' . $request->email);
            
            if (!$storedPersonaId || $storedPersonaId != $request->persona_id) {
                return response()->json([
                    'success' => false,
                    'error' => 'No hay un proceso de verificación pendiente para este email.'
                ], 422);
            }

            // Generar nuevo código
            $newCode = Str::upper(Str::random(8));
            
            // Actualizar el código en caché
            Cache::put('verification_code_' . $request->email, $newCode, now()->addMinutes(30));
            
            // Reiniciar estado de verificación
            Cache::put('email_verified_' . $request->email . '_' . $request->persona_id, false, now()->addMinutes(30));
            
            // Enviar el nuevo código
            Mail::to($request->email)->send(new UserVerificationMail($newCode));
            
            return response()->json([
                'success' => true,
                'message' => 'Nuevo código de verificación enviado.',
                'code' => $newCode
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al reenviar código: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al reenviar el código.'
            ], 500);
        }
    }

    public function activate(User $user)
{
    $user->update(['status' => 'activo']);
    
    return response()->json([
        'success' => true,
        'message' => 'Usuario activado correctamente'
    ]);
}

public function deactivate(User $user)
{
    $user->update(['status' => 'inactivo']);
    
    return response()->json([
        'success' => true,
        'message' => 'Usuario desactivado correctamente'
    ]);
}
}