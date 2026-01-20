<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NosotrosController extends Controller
{
    public function index()
    {
        // Historia de la empresa
        $historia = [
            [
                'anio' => '2024',
                'titulo' => 'Fundación',
                'descripcion' => 'Nacimos como una pequeña empresa familiar el 26 de octubre del año 2024 desde la iniciativa de dos hermanos apoyados por su padre. Lo que comenzaría como un sueño compartido, pronto se transformó en un proyecto lleno de dedicación y visión.'
            ],
            [
                'anio' => '2024',
                'titulo' => 'Inversión',
                'descripcion' => 'Iniciamos con una venta de 5 a 10 sacos semanales, alcanzando en poco tiempo una incrementación de 150 a 250 sacos semanales, siendo el equivalente mensual de una gandola. Este crecimiento fue impulsado por la calidad constante de nuestro alimento y la fidelidad de una clientela que, mediante recomendación, amplió de forma orgánica nuestra base de consumidores.'
            ],
            [
                'anio' => '2025',
                'titulo' => 'Inicio de ventas',
                'descripcion' => 'Al comienzo se inició con la venta de 5 a 10 sacos semanales y poco a poco se fueron incrementando las ventas de 150 a 250 sacos semanales siendo este el equivalente a una gandola de alimento mensual. Gracias a vender un alimento de calidad y por nuestra clientela fiel que nos recomendaba a más gente.'
            ]
        ];

        // Valores de la empresa
        $valores = [
            [
                'icono' => 'fas fa-medal',
                'titulo' => 'Calidad',
                'descripcion' => 'Nos comprometemos con la excelencia en cada producto que elaboramos, utilizando los mejores ingredientes y procesos controlados.'
            ],
            [
                'icono' => 'fas fa-handshake',
                'titulo' => 'Confianza',
                'descripcion' => 'Construimos relaciones duraderas basadas en la transparencia, honestidad y cumplimiento de nuestras promesas.'
            ],
            [
                'icono' => 'fas fa-lightbulb',
                'titulo' => 'Innovación',
                'descripcion' => 'Buscamos constantemente mejoras en nuestros productos y procesos para ofrecer soluciones avanzadas a nuestros clientes.'
            ],
            [
                'icono' => 'fas fa-leaf',
                'titulo' => 'Sostenibilidad',
                'descripcion' => 'Nos responsabilizamos con el medio ambiente y las futuras generaciones mediante prácticas responsables y eficientes.'
            ]
        ];

        // Misión y visión
        $misionVision = [
            'mision' => [
                'icono' => 'fas fa-bullseye',
                'titulo' => 'Misión',
                'descripcion' => 'Producir y distribuir alimentos balanceados de la más alta calidad para animales de granja, contribuyendo al crecimiento y desarrollo del sector agropecuario nacional. Nos comprometemos a ofrecer soluciones nutricionales innovadoras que maximicen el potencial de nuestros clientes, garantizando el bienestar animal y promoviendo prácticas sostenibles en toda nuestra cadena de valor.'
            ],
            'vision' => [
                'icono' => 'fas fa-eye',
                'titulo' => 'Visión',
                'descripcion' => 'Ser la empresa líder en nutrición animal a nivel nacional, reconocida por nuestra excelencia, innovación y compromiso con el desarrollo sostenible del sector agropecuario. Aspiramos a expandir nuestra presencia internacionalmente, manteniendo siempre los más altos estándares de calidad y servicio, mientras contribuimos a la seguridad alimentaria y al progreso de las comunidades donde operamos.'
            ]
        ];

        // Equipo de trabajo
        $equipo = [
            [
                'nombre' => 'Carlos Rodríguez',
                'cargo' => 'Director general',
                'descripcion' => 'Ingeniero Agrónomo con más de 25 años de experiencia en nutrición animal.',
                'imagen' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80'
            ],
            [
                'nombre' => 'María González',
                'cargo' => 'Jefa de producción',
                'descripcion' => 'Especialista en procesos de fabricación de alimentos balanceados con 15 años en la industria.',
                'imagen' => 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80'
            ],
            [
                'nombre' => 'Roberto Sánchez',
                'cargo' => 'Veterinario jefe',
                'descripcion' => 'Doctor en Ciencias Veterinarias especializado en nutrición animal y salud de rumiantes.',
                'imagen' => 'https://images.unsplash.com/photo-1568967739548-a3bca40d2d8a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80'
            ],
            [
                'nombre' => 'Ana Martínez',
                'cargo' => 'Jefa de control de calidad',
                'descripcion' => 'Ingeniera en Alimentos con maestría en sistemas de gestión de calidad agroindustrial.',
                'imagen' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1376&q=80'
            ]
        ];

        return view('Homepage.nosotros', compact('historia', 'valores', 'misionVision', 'equipo'));
    }
}