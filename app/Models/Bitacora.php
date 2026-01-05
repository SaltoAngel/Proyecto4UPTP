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

        public function getHora12Attribute()
    {
        return $this->created_at->format('h:i A'); // Ej: 02:30 PM
    }

        public function getFecha12Attribute()
    {
        return $this->created_at->format('d/m/Y h:i A'); // Ej: 06/01/2024 02:30 PM
    }
        public function getFechaLegibleAttribute()
    {
        $fecha = $this->created_at;
        $hoy = now()->startOfDay();
        
        if ($fecha->isToday()) {
            return 'Hoy ' . $fecha->format('h:i A');
        } elseif ($fecha->isYesterday()) {
            return 'Ayer ' . $fecha->format('h:i A');
        } elseif ($fecha->diffInDays($hoy) < 7) {
            return $fecha->locale('es')->dayName . ' ' . $fecha->format('h:i A');
        } else {
            return $fecha->format('d/m/Y h:i A');
        }
    }
}
