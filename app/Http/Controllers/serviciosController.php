<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = [
            [
                'titulo' => 'Producción de alimentos balanceados',
                'descripcion' => 'Fabricamos alimentos de alta calidad nutricional para todas las especies de granja, utilizando ingredientes seleccionados y procesos controlados que garantizan la máxima eficiencia alimenticia.',
                'imagen' => 'https://4368135.fs1.hubspotusercontent-na1.net/hubfs/4368135/carros-de-alimentacion-1.jpg',
                'id' => 'produccion',
                'features' => [
                    'Fórmulas especializadas por especie y etapa de desarrollo',
                    'Control de calidad en cada etapa de producción',
                    'Ingredientes 100% nutritivos',
                    'Procesos certificados y estandarizados'
                ]
            ],
            [
                'titulo' => 'Despacho de alimento',
                'descripcion' => 'Contamos con despacho en nuestra sede, donde nuestros clientes podrán retirar sus pedidos de nuestros productos, garantizando frescura y calidad en cada entrega.',
                'imagen' => 'https://img.freepik.com/fotos-premium/camion-cargado-sacos-que-contienen-cacahuetes-transporte-india_729664-198.jpg',
                'id' => 'distribucion',
                'features' => [
                    'Entregas programadas y urgentes',
                    'Flota especializada para productos agropecuarios',
                    'Despacho de pedidos personalizados'
                ]
            ],
            [
                'titulo' => 'Asesoría técnica especializada',
                'descripcion' => 'Nuestro equipo de nutricionistas brinda asesoramiento personalizado para optimizar la alimentación, manejo y salud de tus animales, maximizando la productividad de tu granja mediante una buena formulación del alimento.',
                'imagen' => 'https://media.istockphoto.com/id/1413761479/es/foto/pareja-madura-que-se-re%C3%BAne-con-asesor-financiero-para-inversiones.jpg?s=612x612&w=0&k=20&c=48v-6w9CkK-uOyD2d5uTChS9EOlCv-bTELZaWw6jCd4=',
                'id' => 'asesoria',
                'features' => [
                    'Diagnóstico nutricional gratuito',
                    'Planes de alimentación personalizados',
                    'Capacitación en manejo animal',
                    'Visitas técnicas a granjas'
                ]
            ]
        ];

        $serviciosComplementarios = [
            [
                'titulo' => 'Nutrición Especializada',
                'descripcion' => 'Formulaciones específicas para animales con necesidades nutricionales particulares o en etapas críticas de desarrollo.',
                'imagen' => 'https://www.icovv.com/wp-content/uploads/2025/08/veterinario-en-bata-de-laboratorio-de-pie-en-el-establo-scaled.jpg'
            ],
            [
                'titulo' => 'Análisis de Suelo y Forrajes',
                'descripcion' => 'Evaluación de la calidad nutricional de pastos y suelos para optimizar la alimentación del ganado en pastoreo.',
                'imagen' => 'https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
            ],
            [
                'titulo' => 'Consultoría en Manejo de Granjas',
                'descripcion' => 'Asesoría integral para la planificación, organización y mejora continua de las operaciones en granjas productivas.',
                'imagen' => 'https://images.unsplash.com/photo-1592394675778-4239b838fb2c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80'
            ]
        ];

        return view('Homepage.servicios', compact('servicios', 'serviciosComplementarios'));
    }
}