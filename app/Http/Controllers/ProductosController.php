<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        // Datos de productos (en un proyecto real esto vendría de una base de datos)
        $productos = [
            [
                'id' => 1,
                'nombre' => "Alimento Multipropósito",
                'categoria' => "General",
                'descripcion' => "Alimento balanceado para todo tipo de animales de granja. Formulado con los mejores ingredientes para garantizar una nutrición completa y equilibrada. Contiene vitaminas A, D, E, K y minerales esenciales para el desarrollo óptimo.",
                'descripcion_corta' => "Alimento balanceado para todo tipo de animales de granja, con vitaminas y minerales esenciales.",
                'precio' => 25.99,
                'peso' => "50 kg",
                'imagen' => "https://i.pinimg.com/736x/51/70/0e/51700e6e8c8d11432f791bb0782ba44f.jpg",
                'stock' => 150,
                'destacado' => true,
                'categoria_completa' => "Alimento General"
            ],
            [
                'id' => 2,
                'nombre' => "Concentrado para Engorde",
                'categoria' => "Cerdo",
                'descripcion' => "Fórmula especializada para cerdos en etapa de engorde. Optimiza el crecimiento y desarrollo muscular, mejorando la conversión alimenticia. Contiene proteínas de alta calidad y aminoácidos esenciales.",
                'descripcion_corta' => "Fórmula especializada para cerdos en etapa de engorde, optimiza el crecimiento.",
                'precio' => 28.50,
                'peso' => "50 kg",
                'imagen' => "https://i.pinimg.com/1200x/cf/ac/44/cfac445d8e744dea69f1232a4115e927.jpg",
                'stock' => 100,
                'destacado' => true,
                'categoria_completa' => "Alimento para Cerdos"
            ],
            [
                'id' => 3,
                'nombre' => "Alimento Iniciador",
                'categoria' => "Pollo",
                'descripcion' => "Nutrición especial para pollitos en sus primeras semanas de vida. Fórmula diseñada para estimular el sistema inmunológico y promover un crecimiento saludable en las etapas iniciales.",
                'descripcion_corta' => "Nutrición especial para pollitos en sus primeras semanas de vida.",
                'precio' => 32.75,
                'peso' => "50 kg",
                'imagen' => "https://i.pinimg.com/736x/c8/20/b7/c820b79c61f0c48ad8fb5b069c22a848.jpg",
                'stock' => 80,
                'destacado' => true,
                'categoria_completa' => "Alimento para Pollos"
            ],
            [
                'id' => 4,
                'nombre' => "Concentrado Lactante",
                'categoria' => "Ternero",
                'descripcion' => "Alimento para terneros recién nacidos, con nutrientes esenciales para el desarrollo inicial. Contiene probióticos que mejoran la digestión y absorción de nutrientes.",
                'descripcion_corta' => "Alimento para terneros recién nacidos, con nutrientes esenciales.",
                'precio' => 35.25,
                'peso' => "25 kg",
                'imagen' => "https://i.pinimg.com/1200x/e5/90/a0/e590a077162a027054399c51cd3d939a.jpg",
                'stock' => 60,
                'destacado' => false,
                'categoria_completa' => "Alimento para Terneros"
            ],
            [
                'id' => 5,
                'nombre' => "Ponedoras Premium",
                'categoria' => "Gallina",
                'descripcion' => "Alimento especializado para gallinas ponedoras de alta producción. Incrementa la calidad y cantidad de huevos, fortaleciendo la cáscara y mejorando el color de la yema.",
                'descripcion_corta' => "Alimento especializado para gallinas ponedoras de alta producción.",
                'precio' => 27.80,
                'peso' => "50 kg",
                'imagen' => "https://images.unsplash.com/photo-1542838135-2d7f3f6d5b3f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80",
                'stock' => 120,
                'destacado' => true,
                'categoria_completa' => "Alimento para Gallinas"
            ],
            [
                'id' => 6,
                'nombre' => "Alimento para Caballos",
                'categoria' => "Equino",
                'descripcion' => "Nutrición completa para caballos de trabajo y deporte. Proporciona la energía necesaria para el rendimiento físico y mantiene la salud del sistema digestivo equino.",
                'descripcion_corta' => "Nutrición completa para caballos de trabajo y deporte.",
                'precio' => 42.90,
                'peso' => "50 kg",
                'imagen' => "https://images.unsplash.com/photo-1589923188651-268a9765e432?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80",
                'stock' => 40,
                'destacado' => false,
                'categoria_completa' => "Alimento para Equinos"
            ]
        ];

        // Categorías para filtros
        $categorias = [
            'all' => 'Todos',
            'General' => 'Multipropósito',
            'Cerdo' => 'Engorde',
            'Pollo' => 'Iniciador',
            'Ternero' => 'Lactante',
            'Gallina' => 'Ponedoras',
            'Equino' => 'Caballos'
        ];

        return view('Homepage.productos', compact('productos', 'categorias'));
    }
}