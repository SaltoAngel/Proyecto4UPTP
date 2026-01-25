<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = [
            [
                'titulo' => 'Producción de alimentos',
                'descripcion' => 'Fabricamos alimentos balanceados de alta calidad para todas las especies de granja.',
                'imagen' => 'https://images.unsplash.com/photo-1589923188651-268a9765e432?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
                'id' => 'produccion'
            ],
            [
                'titulo' => 'Despacho de alimento',
                'descripcion' => 'Red de distribución que llega a todas las regiones del país con entregas oportunas.',
                'imagen' => 'https://img.freepik.com/fotos-premium/camion-cargado-sacos-que-contienen-cacahuetes-transporte-india_729664-198.jpg',
                'id' => 'distribucion'
            ],
            [
                'titulo' => 'Asesoría técnica',
                'descripcion' => 'Asesoramiento personalizado para optimizar la alimentación y manejo de tus animales.',
                'imagen' => 'https://media.istockphoto.com/id/1413761479/es/foto/pareja-madura-que-se-re%C3%BAne-con-asesor-financiero-para-inversiones.jpg?s=612x612&w=0&k=20&c=48v-6w9CkK-uOyD2d5uTChS9EOlCv-bTELZaWw6jCd4=',
                'id' => 'asesoria'
            ]
        ];

        return view('homepage.servicios', compact('servicios'));
    }
}