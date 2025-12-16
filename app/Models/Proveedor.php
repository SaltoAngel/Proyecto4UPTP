<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Tabla explícita: evitar pluralización incorrecta (proveedors)
    protected $table = 'proveedores';

    protected $fillable = [
        'persona_id',
        'codigo_proveedor',
        'categoria',
        'productos_servicios',
        'especializacion',
        'contacto_comercial',
        'telefono_comercial',
        'email_comercial',
        'calificacion',
        'observaciones_calificacion',
        'fecha_ultima_evaluacion',
        'estado',
        'fecha_registro',
        'fecha_ultima_compra',
        'monto_total_compras',
        'banco',
        'tipo_cuenta',
        'numero_cuenta',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'fecha_ultima_compra' => 'date',
        'fecha_ultima_evaluacion' => 'date',
        'monto_total_compras' => 'decimal:2',
    ];

    public static function generarCodigo(): string
    {
        $secuencia = (self::withTrashed()->max('id') ?? 0) + 1;
        return 'PROV-' . str_pad($secuencia, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Proveedor $model) {
            if (empty($model->codigo_proveedor)) {
                // Genera un código secuencial amigable
                $model->codigo_proveedor = self::generarCodigo();
            }
        });
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_id');
    }

    public function tiposProveedores(): BelongsToMany
    {
        return $this->belongsToMany(TiposProveedores::class, 'proveedores_tipos', 'proveedor_id', 'tipo_proveedor_id');
    }

    public function repuestos(): HasMany
    {
        return $this->hasMany(Repuesto::class);
    }

    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(MateriaPrima::class);
    }

    public function hasProveedores(string $hasProveedores): bool
    {
        return $this->tiposProveedores()->where('nombre_tipo', $hasProveedores)->exists();
    }
}
