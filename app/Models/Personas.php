<?php
/**
 * Modelo: Personas
 * Propósito: Representa personas naturales o jurídicas vinculadas al sistema.
 * Tabla: personas (nombre personalizado en migración)
 * Atributos:
 *  - nombres, apellidos, razon_social, nombre_comercial, documento, contacto, etc.
 * Relación:
 *  - user(): hasOne User (usuario asociado)
 * Utilidades:
 *  - generarCodigo(): crea código legible PER-<TIPO>-0001
 *  - getNombreCompletoAttribute(): devuelve nombre completo (natural/jurídica)
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personas extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Tabla personalizada (migración crea 'persona')
    protected $table = 'personas';

    /**
     * Atributos asignables.
     */
    protected $fillable = [
        'codigo',
        'tipo',
        'nombres',
        'apellidos',
        'razon_social',
        'nombre_comercial',
        'tipo_documento',
        'documento',
        'direccion',
        'estado',
        'ciudad',
        'telefono',
        'telefono_alternativo',
        'email',
        'activo',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: una persona puede tener un usuario (o ninguno).
     */
    public function user()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

        public function getNombreCompletoAttribute()
    {
        if ($this->tipo === 'juridica') {
            // Para persona jurídica
            if (!empty($this->razon_social)) {
                return $this->razon_social;
            }
            if (!empty($this->nombre_comercial)) {
                return $this->nombre_comercial;
            }
            return 'Empresa sin nombre';
        }
        
        // Para persona natural
        $nombreCompleto = trim($this->nombres . ' ' . $this->apellidos);
        
        return !empty($nombreCompleto) ? $nombreCompleto : 'Persona sin nombre';
    }

    /**
     * Genera un código legible para la persona según el tipo de documento.
     * Formato: PER-{TIPODOC}-{0001}
     */
    public static function generarCodigo(string $tipoDocumento): string
    {
        $secuencia = (self::withTrashed()->max('id') ?? 0) + 1;
        $tipoDoc = strtoupper($tipoDocumento);
        return 'PER-' . $tipoDoc . '-' . str_pad($secuencia, 4, '0', STR_PAD_LEFT);
    }
}
