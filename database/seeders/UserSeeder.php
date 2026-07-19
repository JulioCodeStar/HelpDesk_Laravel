<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cuentas conocidas para pruebas (password: "password")
        User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@helpdesk.test',
        ]);

        User::factory()->agente()->create([
            'name' => 'Agente Demo',
            'email' => 'agente@helpdesk.test',
        ]);

        User::factory()->cliente()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@helpdesk.test',
        ]);

        // Resto de usuarios (7 agentes + 20 clientes = 27) → total 30
        User::factory()->agente()->count(7)->create();
        User::factory()->cliente()->count(20)->create();
    }
}
