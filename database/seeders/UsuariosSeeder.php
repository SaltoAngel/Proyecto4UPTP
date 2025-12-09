<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $usuarios = [
            [
                'name' => 'Daniel Perez',
                'email' => 'danielelpro19@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'remember_token' => Str::random(10),
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],

             [
                'name' => 'Mariexi Oviedo',
                'email' => 'mariexioviedo@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'remember_token' => Str::random(10),
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elyanni Túa',
                'email' => 'elyannitua@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'remember_token' => Str::random(10),
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gabriela Rivero',
                'email' => 'grivero115@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'remember_token' => Str::random(10),
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Falcon',
                'email' => 'mfrosendo07@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'remember_token' => Str::random(10),
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($usuarios);
    }
}
