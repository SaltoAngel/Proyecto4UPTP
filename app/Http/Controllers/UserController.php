<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserVerificationMail;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $estado = $request->input('estado', '');

        // Cargar persona y roles para Spatie
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
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('users.user', compact('users'));
    }

    public function create()
    {
        $personas = Persona::whereDoesntHave('user')
            ->select(
                DB::raw("CONCAT(nombres, ' ', apellidos, ' - ', documento) as nombre_completo"),
                'id',
                'email',
                'documento'
            )
            ->get();

        $roles = Role::all();

        return view('users.create', compact('personas', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'verification_code' => 'nullable|size:6',
        ]);

        try {
            DB::beginTransaction();

            // Buscar la persona
            $persona = Persona::findOrFail($request->persona_id);

            // Verificar que el email coincida con la persona
            if ($persona->email !== $request->email) {
                return back()->withErrors(['email' => 'El email no coincide con el de la persona seleccionada.']);
            }

            // Si hay código de verificación, validarlo
            if ($request->has('verification_code')) {
                $existingUser = User::where('email', $request->email)->first();
                
                if (!$existingUser || !$existingUser->isValidVerificationCode($request->verification_code)) {
                    return back()->withErrors(['verification_code' => 'Código de verificación inválido o expirado.']);
                }

                // Código válido, marcar como verificado
                $existingUser->markAsVerified();
                
                DB::commit();
                return redirect()->route('users.user')->with('success', 'Usuario verificado exitosamente.');
            }

            // Crear nuevo usuario
            $password = str_replace(['.', '-'], '', $persona->documento);

            $user = User::create([
                'email' => $persona->email,
                'password' => Hash::make($password),
                'persona_id' => $persona->id,
                'status' => 'pendiente', // Consistente con el modelo
                // ELIMINADO: 'role_id' => $request->role_id, - Spatie maneja roles aparte
            ]);

            // Asignar rol usando Spatie (sin usar role_id)
            $role = Role::find($request->role_id);
            $user->assignRole($role->name);

            // Generar y enviar código de verificación
            $verificationCode = $user->generateVerificationCode();
            
            // Enviar email con código
            Mail::to($user->email)->send(new UserVerificationMail($verificationCode));

            DB::commit();

            return back()->with([
                'success' => 'Usuario creado exitosamente. Se ha enviado un código de verificación al email.',
                'verification_sent' => true,
                'email' => $user->email,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }

        try {
            // Generar nuevo código usando el método del modelo
            $verificationCode = $user->generateVerificationCode();
            
            // Enviar email
            Mail::to($user->email)->send(new UserVerificationMail($verificationCode));

            return response()->json([
                'success' => true,
                'message' => 'Código de verificación enviado exitosamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al enviar el código: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        //Solo cargar persona, Spatie maneja roles
        $user = User::with(['persona'])->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        // Solo cargar persona
        $user = User::with(['persona'])->findOrFail($id);
        $roles = Role::all();
        
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:pendiente,activo,inactivo',
        ]);

        // No actualizar role_id en el modelo User
        // Spatie maneja roles en su propia tabla
        
        // Actualizar solo el estado
        $user->update([
            'status' => $request->status,
        ]);

        // Actualizar roles usando Spatie (sin role_id)
        $newRole = Role::find($request->role_id);
        $user->syncRoles([$newRole->name]);

        return redirect()->route('users.user')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.user')->with('success', 'Usuario eliminado exitosamente.');
    }
}