<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['name' => 'Baja',  'response_time_minutes' => 1440], // 24 horas
            ['name' => 'Media', 'response_time_minutes' => 480],  // 8 horas
            ['name' => 'Alta',  'response_time_minutes' => 120],  // 2 horas
        ];

        foreach ($priorities as $priority) {
            Priority::create($priority);
        }
    }
}
