<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoriasMateriasPrimas;

class Categorias_Materias extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoriasMateriasPrimas::create([
            'nombre_categoria' => 'Cereales',
            'descripcion' => 'Granos y subproductos de cereales utilizados como fuente de energía y carbohidratos en la alimentación animal (maíz, trigo, sorgo, arroz, etc.)',
        ]);
        
        CategoriasMateriasPrimas::create([
            'nombre_categoria' => 'Proteinas',
            'descripcion' => 'Ingredientes de origen animal y vegetal que aportan proteínas esenciales para el crecimiento y desarrollo (harina de soya, pescado, carne, etc.)',
        ]);
        
        CategoriasMateriasPrimas::create([
            'nombre_categoria' => 'Minerales',
            'descripcion' => 'Suplementos minerales esenciales para el metabolismo, formación ósea y funciones vitales (calcio, fósforo, sal, oligoelementos, etc.)',
        ]);
        
        CategoriasMateriasPrimas::create([
            'nombre_categoria' => 'Vitaminas',
            'descripcion' => 'Suplementos vitamínicos necesarios para el correcto funcionamiento del organismo animal y prevención de deficiencias nutricionales',
        ]);
    }
}
