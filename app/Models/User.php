<?php
/**
 * Modelo: User
 * Propósito: Representa a los usuarios del sistema (autenticación, estados, verificación).
 * 
 * Combina características de ambas versiones:
 * - Sistema de bitácora y eventos del primer archivo
 * - Métodos de verificación del segundo archivo
 * - Sistema de 2FA avanzado (deshabilitado pero disponible)
 * - Spatie Permission ya maneja roles automáticamente
 * 
 * Relaciones:
 *  - persona(): belongsTo Personas (datos personales)
 *  - roles: belongsToMany (manejado por Spatie\Permission)
 * 
 * Atributos relevantes:
 *  - status, last_login_at, is_verified, verification_code
 *  - login_count, first_login_completed, next_2fa_attempt
 * 
 * Scopes:
 *  - scopeActive(), scopeVerified()
 * 
 * Utilidades:
 *  - getFullNameAttribute(): obtiene nombre completo desde persona
 *  - isPending(): verifica si el usuario está pendiente
 *  - isValidVerificationCode(): valida código de verificación (30 min)
 *  - generateVerificationCode(): genera nuevo código
 *  - markAsVerified(): marca usuario como verificado
 * 
 * Nota:
 *  - Métodos 2FA están presentes pero deshabilitados (comentados)
 *  - Sistema de bitácora activo para auditoría
 *  - Spatie\Permission maneja roles automáticamente (no necesita relación role())
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Bitacora;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'persona_id',
        'status',
        'last_login_at',
        'verification_code',
        'verification_code_sent_at',
        'is_verified',
        'login_count',
        'first_login_completed',
        'next_2fa_attempt'
        // Nota: 'email_verified_at' no está en fillable porque se maneja con markAsVerified()
    ];

    /**
     * Atributos ocultos en serializaciones (JSON, arrays)
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code'
    ];

    /**
     * Casts de atributos para conversión automática de tipos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'verification_code_sent_at' => 'datetime',
        'is_verified' => 'boolean',
        'first_login_completed' => 'boolean',
        'next_2fa_attempt' => 'integer',
        'created_at' => 'datetime', // Agregado del segundo archivo
        'updated_at' => 'datetime', // Agregado del segundo archivo
    ];

    /**
     * Valores por defecto para nuevos registros
     */
    protected $attributes = [
        'status' => 'pendiente', // Cambiado de 'activo' a 'pendiente' (del segundo archivo)
        'login_count' => 0,
        'first_login_completed' => false,
        'is_verified' => false
    ];

    // ===== MÉTODOS DE AUTENTICACIÓN 2FA - DESHABILITADOS =====
    
    /**
     * Generar código de verificación (versión avanzada)
     */
    // public function generateVerificationCode()
    // {
    //     $code = sprintf("%06d", mt_rand(1, 999999));
    //     
    //     $this->update([
    //         'verification_code' => $code,
    //         'verification_code_sent_at' => now(),
    //         'is_verified' => false,
    //     ]);
    //
    //     return $code;
    // }

    /**
     * Verificar código (versión avanzada - 15 minutos)
     */
    // public function verifyCode($code)
    // {
    //     // Verificar que el código existe y coincide
    //     if (!$this->verification_code || $this->verification_code !== $code) {
    //         return false;
    //     }
    //
    //     // Verificar que no ha expirado (15 minutos)
    //     if (!$this->verification_code_sent_at || 
    //         $this->verification_code_sent_at->diffInMinutes(now()) > 15) {
    //         return false;
    //     }
    //
    //     // Limpiar el código y marcar como verificado
    //     $this->update([
    //         'verification_code' => null,
    //         'verification_code_sent_at' => null,
    //         'is_verified' => true,
    //     ]);
    //
    //     return true;
    // }

    /**
     * Incrementar contador de inicios de sesión
     */
    // public function incrementLoginCount()
    // {
    //     $this->login_count++;
    //     
    //     // Marcar que completó el primer inicio de sesión
    //     if (!$this->first_login_completed) {
    //         $this->first_login_completed = true;
    //     }
    //     
    //     $this->save();
    //     return $this;
    // }

    /**
     * Determinar si se requiere 2FA para este inicio de sesión
     */
    // public function shouldRequire2FA()
    // {
    //     // CASO 1: Primera vez que inicia sesión - SIEMPRE requiere 2FA
    //     if (!$this->first_login_completed) {
    //         return true;
    //     }
    //
    //     // CASO 2: Ya se configuró el próximo intento específico
    //     if ($this->next_2fa_attempt !== null) {
    //         if ($this->login_count >= $this->next_2fa_attempt) {
    //             // Limpiar el siguiente intento ya que se cumplió
    //             $this->next_2fa_attempt = null;
    //             $this->save();
    //             return true;
    //         }
    //         return false;
    //     }
    //
    //     // CASO 3: Sistema aleatorio - 1 de cada 6 veces (16.6% de probabilidad)
    //     $roll = random_int(1, 6);
    //     $requires2FA = ($roll === 1); // Solo cuando sale 1
    //     
    //     if ($requires2FA) {
    //         // Configurar cuándo será el próximo 2FA (entre 1 y 6 intentos después)
    //         $nextAttempt = $this->login_count + random_int(1, 6);
    //         $this->next_2fa_attempt = $nextAttempt;
    //         $this->save();
    //         
    //         return true;
    //     }
    //
    //     return false;
    // }

    /**
     * Obtener estadísticas de uso de 2FA
     */
    // public function get2FAStats()
    // {
    //     $nextRequirement = 'Desconocido';
    //     
    //     if ($this->next_2fa_attempt !== null) {
    //         $attemptsUntil2FA = $this->next_2fa_attempt - $this->login_count;
    //         $nextRequirement = $attemptsUntil2FA > 0 ? 
    //             "En {$attemptsUntil2FA} intento(s)" : 
    //             "Próximo inicio de sesión";
    //     }
    //
    //     return [
    //         'total_logins' => $this->login_count,
    //         'first_login_completed' => $this->first_login_completed,
    //         'next_2fa_at_login' => $this->next_2fa_attempt,
    //         'next_2fa_in_attempts' => $nextRequirement,
    //         'probability' => '16.6% (1 de cada 6 en promedio)',
    //     ];
    // }

    /**
     * Forzar 2FA en el próximo inicio de sesión
     */
    // public function force2FANextTime()
    // {
    //     $this->next_2fa_attempt = $this->login_count + 1;
    //     $this->save();
    //     return $this;
    // }
    
    // ===== FIN MÉTODOS 2FA =====

    // ===== MÉTODOS DEL SEGUNDO ARCHIVO =====

    /**
     * Verificar si el usuario está pendiente
     * (Del segundo archivo)
     * 
     * @return bool True si el estado es 'pendiente'
     */
    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }

    /**
     * Verificar si el código de verificación es válido
     * (Del segundo archivo - validez de 30 minutos)
     * 
     * @param string $code Código a verificar
     * @return bool True si el código es válido y no ha expirado
     */
    public function isValidVerificationCode($code): bool
    {
        return $this->verification_code === $code && 
               $this->verification_code_sent_at &&
               $this->verification_code_sent_at->addMinutes(30)->isFuture();
    }

    /**
     * Generar código de verificación
     * (Del segundo archivo - versión básica)
     * 
     * @return string Código de 6 dígitos
     */
    public function generateVerificationCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'verification_code' => $code,
            'verification_code_sent_at' => now(),
        ]);
        return $code;
    }

    /**
     * Marcar como verificado
     * (Del segundo archivo)
     * 
     * Limpia el código de verificación y marca el email como verificado
     */
    public function markAsVerified(): void
    {
        $this->update([
            'verification_code' => null,
            'verification_code_sent_at' => null,
            'email_verified_at' => now(),
            'status' => 'activo', // Cambia estado de 'pendiente' a 'activo' al verificar
        ]);
    }

    // ===== FIN MÉTODOS DEL SEGUNDO ARCHIVO =====

    // ===== SCOPES Y MÉTODOS DEL PRIMER ARCHIVO =====

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'activo');
    }

    /**
     * Scope para usuarios verificados
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive()
    {
        return $this->status === 'activo';
    }

    /**
     * Verificar si el usuario está verificado
     */
    public function isVerified()
    {
        return $this->is_verified;
    }

    // ===== RELACIONES =====

    /**
     * Relación: el usuario pertenece a una persona
     * (Del primer archivo - Personas::class)
     * 
     * Nota: Se mantiene el nombre Personas::class según el primer archivo
     */
    public function persona()
    {
        return $this->belongsTo(Personas::class, 'persona_id');
    }

    /**
     * NOTA: No se necesita relación role() explícita
     * Spatie\Permission\Traits\HasRoles ya provee:
     * - $user->roles (belongsToMany)
     * - $user->assignRole()
     * - $user->removeRole()
     * - $user->hasRole()
     * - $user->hasAllRoles()
     * - $user->hasAnyRole()
     * 
     * El campo 'role_id' en $fillable se mantiene por compatibilidad,
     * pero Spatie usa su propia tabla 'model_has_roles'
     */

    /**
 * Accesor: nombre del rol (para compatibilidad con vistas)
 * Reemplaza el uso de $user->role->name
 */
public function getRoleNameAttribute()
{
    return $this->roles->first()?->name ?? 'Sin rol';
}


    /**
     * Accesor: nombre completo desde la persona relacionada.
     * (Del primer archivo - con seguridad mejorada)
     */
    public function getFullNameAttribute()
    {
        // Verificar que la relación esté cargada y exista
        if (!$this->relationLoaded('persona')) {
            $this->load('persona');
        }
        
        return optional($this->persona)->full_name;
    }



    // ===== SISTEMA DE BITÁCORA (DEL PRIMER ARCHIVO) =====

    /**
     * Booted: Configuración de eventos del modelo
     * Sistema completo de bitácora para auditoría
     */
    protected static function booted()
    {
        // Sanitiza datos para no registrar credenciales en bitácora
        $filtrar = fn(array $payload) => collect($payload)
            ->except(['password', 'remember_token', 'verification_code'])
            ->toArray();

        // Evento: Cuando se crea un nuevo usuario
        static::created(function (User $user) use ($filtrar) {
            Bitacora::registrar(
                'usuarios',
                'crear',
                'Creó usuario ID ' . $user->id,
                null,
                $filtrar($user->getAttributes())
            );
        });

        // Evento: Antes de actualizar un usuario
        static::updating(function (User $user) use ($filtrar) {
            $original = $filtrar($user->getOriginal());
            $changes = $user->getDirty();

            // Evitar ruido por campos de sesión/actividad
            $camposRuido = ['last_login_at', 'updated_at', 'next_2fa_attempt', 'login_count'];
            $camposSignificativos = array_diff(array_keys($changes), $camposRuido);
            if (empty($camposSignificativos)) {
                return;
            }

            $accion = 'actualizar';
            $detalle = 'Actualizó usuario ID ' . $user->id;

            // Detectar cambios específicos de estado
            if (array_key_exists('status', $changes)) {
                $nuevo = $changes['status'];
                $anterior = $original['status'] ?? null;
                if ($nuevo === 'bloqueado') {
                    $accion = 'bloquear';
                    $detalle = 'Bloqueó usuario ID ' . $user->id;
                } elseif ($nuevo !== 'activo' && $anterior !== $nuevo) {
                    $accion = 'actualizar_estado';
                    $detalle = 'Actualizó estado de usuario ID ' . $user->id . " ({$anterior} -> {$nuevo})";
                } elseif ($nuevo === 'activo' && $anterior !== 'activo') {
                    $accion = 'restaurar';
                    $detalle = 'Reactivó usuario ID ' . $user->id;
                }
            }

            Bitacora::registrar(
                'usuarios',
                $accion,
                $detalle,
                $original,
                $filtrar($user->getAttributes())
            );
        });

        // Evento: Cuando se elimina (soft delete) un usuario
        static::deleted(function (User $user) use ($filtrar) {
            Bitacora::registrar(
                'usuarios',
                'deshabilitar',
                'Deshabilitó usuario ID ' . $user->id,
                $filtrar($user->getOriginal()),
                $filtrar($user->getAttributes())
            );
        });

        // Evento: Cuando se restaura un usuario eliminado
        static::restored(function (User $user) use ($filtrar) {
            Bitacora::registrar(
                'usuarios',
                'restaurar',
                'Restauró usuario ID ' . $user->id,
                null,
                $filtrar($user->getAttributes())
            );
        });
    }
}