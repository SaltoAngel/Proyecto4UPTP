<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMailable;

class ContactoController extends Controller
{
    public function index()
    {
        // Horarios de atención
        $horarios = [
            'trabajo' => [
                'titulo' => 'Horario de trabajo',
                'horarios' => [
                    ['dias' => 'Lunes a Sábado', 'horas' => '8:00 am - 5:00 pm'],
                    ['dias' => 'Domingos', 'horas' => 'Hasta el medio día']
                ]
            ],
            'pedidos' => [
                'titulo' => 'Recibo de pedidos',
                'horarios' => [
                    ['dias' => 'Lunes a sábado', 'horas' => '24 horas'],
                    ['dias' => 'Domingos', 'horas' => '6:00 am - 10:00 pm']
                ]
            ],
            'despacho' => [
                'titulo' => 'Despacho',
                'horarios' => [
                    ['dias' => 'Lunes a sábado', 'horas' => '8:00 am - 5:00 pm'],
                    ['dias' => 'Domingo', 'horas' => 'Hasta el medio día']
                ]
            ]
        ];

        // Preguntas frecuentes
        $preguntasFrecuentes = [
            [
                'pregunta' => '¿Cuál es el tiempo de entrega de los pedidos?',
                'respuesta' => 'El tiempo de entrega es de 2-5 días hábiles.'
            ],
            [
                'pregunta' => '¿Realizan asesorías técnicas en granjas?',
                'respuesta' => 'Absolutamente. Nuestro equipo de producción y nutricionistas ofrecen asesorías en relación al alimento que mejor puedan comprar para sus animales debido a sus etapas, evaluando el tiempo que tiene el animal y la cantidad que sean, recomendando así un buen alimento formulado para el crecimiento correcto.'
            ],
            [
                'pregunta' => '¿Tienen productos especializados para animales con necesidades específicas?',
                'respuesta' => 'Sí, desarrollamos fórmulas especializadas para animales en diferentes condiciones: alimentos para animales en gestación/lactancia, para crecimiento acelerado, para animales convalecientes, dietas hipoalergénicas y alimentos medicados bajo prescripción veterinaria. Consulte con nuestro equipo técnico para más detalles.'
            ]
        ];

        // Asuntos para el formulario
        $asuntos = [
            '' => 'Selecciona una opción',
            'cotizacion' => 'Solicitud de cotización',
            'asesoria' => 'Asesoría técnica',
            'pedido' => 'Seguimiento de pedido',
            'reclamo' => 'Reclamo o sugerencia',
            'general' => 'Consulta general'
        ];

        // Información de contacto
        $contacto = [
            'direccion' => 'Vía Av circunvalación sur, a 200 mts del semáforo de la entrada Alto de Camoruco',
            'planta' => 'Nos encontramos ubicados detrás de la ferretería metrofer',
            'telefono' => '+58 424-5487471',
            'email' => 'nupilcacorreos@gmail.com',
            'google_maps' => 'https://www.google.com/maps/place/Comercializadora+metrofer/@9.5358672,-69.1979815,268m/data=!3m1!1e3!4m6!3m5!1s0x8e7dc100144961c9:0x9619fe98ad808172!8m2!3d9.535667!4d-69.1967472!16s%2Fg%2F11w7lm1n41?hl=es&entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoASAFQAw%3D%3D'
        ];

        return view('Homepage.contacto', compact('horarios', 'preguntasFrecuentes', 'asuntos', 'contacto'));
    }

    public function enviar(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'asunto' => 'required|string',
            'mensaje' => 'required|string|min:10|max:1000'
        ]);

        // Datos del formulario
        $datos = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'fecha' => now()->format('d/m/Y H:i:s')
        ];

        // Enviar correo (descomentar cuando configures el mail)
        // Mail::to('nupilcacorreos@gmail.com')->send(new ContactoMailable($datos));

        // Guardar en la base de datos (opcional)
        // Contacto::create($datos);

        return response()->json([
            'success' => true,
            'message' => '¡Gracias ' . $request->nombre . '! Tu mensaje ha sido enviado correctamente. Te contactaremos en menos de 24 horas.'
        ]);
    }
}