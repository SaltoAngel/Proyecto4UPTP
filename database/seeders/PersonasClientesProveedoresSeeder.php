<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonasClientesProveedoresSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1) Comercio que compra (solo cliente)
        $comercioPersonaId = DB::table('personas')->insertGetId([
            'codigo' => 'PER-J-0001',
            'tipo' => 'juridica',
            'razon_social' => 'Comercial La Estrella C.A.',
            'nombre_comercial' => 'La Estrella',
            'tipo_documento' => 'J',
            'documento' => '12345678-1',
            'direccion' => 'Av. Principal de Centro, Local 12',
            'estado' => 'Distrito Capital',
            'ciudad' => 'Caracas',
            'telefono' => '0212-5550001',
            'telefono_alternativo' => '0414-5550001',
            'email' => 'contacto@laestrella.com',
            'activo' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('clientes')->insert([
            'persona_id' => $comercioPersonaId,
            'codigo_cliente' => 'CLI-J-0001',
            'tipo_cliente' => 'mayorista',
            'contacto_comercial' => 'Ana Torres',
            'telefono_comercial' => '0414-5550002',
            'email_comercial' => 'ventas@laestrella.com',
            'estado' => true,
            'exento_impuestos' => false,
            'regimen_fiscal' => 'Ordinario',
            'fecha_registro' => $now->toDateString(),
            'fecha_ultima_actualizacion' => $now->toDateString(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2) Ente gubernamental que compra
        $gobiernoPersonaId = DB::table('personas')->insertGetId([
            'codigo' => 'PER-G-0002',
            'tipo' => 'juridica',
            'razon_social' => 'Instituto Nacional de Obras Públicas',
            'nombre_comercial' => 'INOP',
            'tipo_documento' => 'G',
            'documento' => 'G-87654321-9',
            'direccion' => 'Av. Libertador, Edif. Sede, Piso 3',
            'estado' => 'Miranda',
            'ciudad' => 'Los Teques',
            'telefono' => '0212-6001000',
            'telefono_alternativo' => null,
            'email' => 'compras@inop.gob.ve',
            'activo' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('clientes')->insert([
            'persona_id' => $gobiernoPersonaId,
            'codigo_cliente' => 'CLI-G-0002',
            'tipo_cliente' => 'corporativo',
            'contacto_comercial' => 'Luis Rivas',
            'telefono_comercial' => '0412-6002000',
            'email_comercial' => 'luis.rivas@inop.gob.ve',
            'estado' => true,
            'exento_impuestos' => true,
            'regimen_fiscal' => 'Gubernamental',
            'fecha_registro' => $now->toDateString(),
            'fecha_ultima_actualizacion' => $now->toDateString(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3) Comercio que compra y además provee
        $mixtoPersonaId = DB::table('personas')->insertGetId([
            'codigo' => 'PER-J-0003',
            'tipo' => 'juridica',
            'razon_social' => 'Agroinsumos Los Andes, C.A.',
            'nombre_comercial' => 'Agro Andes',
            'tipo_documento' => 'J',
            'documento' => '11223344-5',
            'direccion' => 'Zona Industrial La Variante, Galpón 5',
            'estado' => 'Lara',
            'ciudad' => 'Barquisimeto',
            'telefono' => '0251-7003344',
            'telefono_alternativo' => '0416-7003344',
            'email' => 'info@agroandes.com',
            'activo' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('clientes')->insert([
            'persona_id' => $mixtoPersonaId,
            'codigo_cliente' => 'CLI-J-0003',
            'tipo_cliente' => 'distribuidor',
            'contacto_comercial' => 'María Fernanda Díaz',
            'telefono_comercial' => '0412-7003345',
            'email_comercial' => 'compras@agroandes.com',
            'estado' => true,
            'exento_impuestos' => false,
            'regimen_fiscal' => 'Ordinario',
            'fecha_registro' => $now->toDateString(),
            'fecha_ultima_actualizacion' => $now->toDateString(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('proveedores')->insert([
            'persona_id' => $mixtoPersonaId,
            'codigo_proveedor' => 'PROV-J-0001',
            'categoria' => 'Materia prima',
            'productos_servicios' => json_encode([
                'Fertilizantes',
                'Semillas certificadas',
                'Repuestos agrícolas'
            ]),
            'especializacion' => 'Insumos para cultivos andinos',
            'calificacion' => 5,
            'observaciones_calificacion' => 'Proveedor confiable y puntual.',
            'fecha_ultima_evaluacion' => $now->toDateString(),
            'estado' => 'activo',
            'fecha_registro' => $now->toDateString(),
            'fecha_ultima_compra' => $now->toDateString(),
            'monto_total_compras' => 150000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Asociar múltiples categorías al proveedor mixto si existen tipos cargados
        $tipoIds = DB::table('tipos_proveedores')->pluck('id')->take(2);
        if ($tipoIds->isNotEmpty()) {
            $rows = $tipoIds->map(fn($id) => [
                'proveedor_id' => 1, // primer proveedor insertado arriba
                'tipo_proveedor_id' => $id,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();
            DB::table('proveedores_tipos')->insert($rows);
        }
    }
}
