<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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
        'role_id',
        'persona_id',
        'status',
        'last_login_at',
        'verification_code',
        'verification_code_sent_at',
        'is_verified',
        'login_count',
        'first_login_completed',
        'next_2fa_attempt'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'verification_code_sent_at' => 'datetime',
        'is_verified' => 'boolean',
        'first_login_completed' => 'boolean',
        'next_2fa_attempt' => 'integer'
    ];

    protected $attributes = [
        'status' => 'activo',
        'login_count' => 0,
        'first_login_completed' => false,
        'is_verified' => false
    ];

    // ===== MÉTODOS DE AUTENTICACIÓN 2FA - DESHABILITADOS =====
    
    /**
     * Generar código de verificación
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
     * Verificar código
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
     * Relación: el usuario pertenece a una persona (opcionalmente).
     */
    public function persona()
    {
        return $this->belongsTo(Personas::class, 'persona_id');
    }

    /**
     * Accesor: nombre completo desde la persona relacionada.
     */
        // Mejorado (con seguridad):
        public function getFullNameAttribute()
        {
            // Verificar que la relación esté cargada y exista
            if (!$this->relationLoaded('persona')) {
                $this->load('persona');
            }
            
            return optional($this->persona)->full_name;
        }

    /**
     * Verificar si el usuario está verificado
     */
    public function isVerified()
    {
        return $this->is_verified;
    }
}