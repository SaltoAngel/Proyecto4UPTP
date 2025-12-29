<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'template',
        'descripcion',
        'categoria',
        'parametros',
        'activo',
        'requiere_db'
    ];

    protected $casts = [
        'parametros' => 'array',
        'activo' => 'boolean',
        'requiere_db' => 'boolean'
    ];

    /**
     * Verifica si el archivo template existe
     */
    public function templateExists()
    {
        return file_exists($this->getTemplatePath());
    }

    /**
     * Obtiene la ruta completa del archivo template
     */
    public function getTemplatePath()
    {
        return app_path("Reports/templates/{$this->template}.jrxml");
    }

    /**
     * Scope para reportes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope por categoría
     */
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Obtiene las categorías disponibles
     */
    public static function getCategorias()
    {
        return self::select('categoria')
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria')
            ->sort();
    }
}
