<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\User;

class PersonaUserIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function database_has_persona_and_users_tables_and_columns()
    {
        // Migrations are run by RefreshDatabase
        $this->assertTrue(Schema::hasTable('persona'));
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasColumn('users', 'persona_id'));
        $this->assertTrue(Schema::hasColumn('persona', 'documento'));
    }

    /** @test */
    public function seeders_create_persona_and_user_and_link_them()
    {
        // Run only the seeders needed for users/roles
        $this->seed(\Database\Seeders\SpatieRolesPermissionsSeeder::class);
        $this->seed(\Database\Seeders\UsuariosSeeder::class);

        // Example user used in seeders
        $email = 'danielelpro19@gmail.com';

        $this->assertDatabaseHas('persona', ['email' => $email]);
        $this->assertDatabaseHas('users', ['email' => $email]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'User created by seeder');
        $this->assertNotNull($user->persona_id, 'User has persona_id set');
        $this->assertDatabaseHas('persona', ['id' => $user->persona_id]);

        // Persona email should match user email (seeder copies it)
        $this->assertEquals($email, $user->persona->email);
    }
}
