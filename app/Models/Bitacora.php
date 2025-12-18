<?php
/**
 * Modelo: Bitacora
 * Propósito: Registro de acciones del sistema (quién, qué, cuándo) con diffs de datos.
 * Tabla: bitacora
 * Atributos:
 *  - codigo, user_id, modulo, accion, detalle, datos_anteriores, datos_nuevos, ip, user_agent
 * Casts:
 *  - datos_anteriores, datos_nuevos como array; timestamps como datetime
 * Relaciones:
 *  - user(): belongsTo User
 * Utilidades:
 *  - generarCodigo(): crea código único con fecha
 *  - registrar(): helper para insertar registros de bitácora desde acciones
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    
    protected $fillable = [
        'codigo',
        'user_id',
        'modulo',
        'accion',
        'detalle',
        'datos_anteriores',
        'datos_nuevos',
        'ip_address',
        'user_agent'
    ];
    
    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Generar código único
    public static function generarCodigo(): string
    {
        do {
            $codigo = 'BIT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('codigo', $codigo)->exists());
        
        return $codigo;
    }
    
    // Registrar cambio
    public static function registrar(string $modulo, string $accion, string $detalle, array $datosAnteriores = null, array $datosNuevos = null)
    {
        return self::create([
            'codigo' => self::generarCodigo(),
            'user_id' => auth()->id(),
            'modulo' => $modulo,
            'accion' => $accion,
            'detalle' => $detalle,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos' => $datosNuevos,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
