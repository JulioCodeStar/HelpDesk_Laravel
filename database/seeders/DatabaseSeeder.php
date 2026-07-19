<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // 1. Catálogos base (sin dependencias)
            DepartmentSeeder::class,
            StatusSeeder::class,
            PrioritySeeder::class,
            CategorySeeder::class,

            // 2. Usuarios (dependen de departments)
            UserSeeder::class,

            // 3. FAQs (independiente)
            FaqSeeder::class,

            // 4. Tickets (dependen de users + catálogos)
            TicketSeeder::class,

            // 5. Actividad sobre los tickets
            TicketMessageSeeder::class,
            LogSeeder::class,
        ]);
    }
}
