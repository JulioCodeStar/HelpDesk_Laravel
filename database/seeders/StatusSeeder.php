<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Abierto',    'color' => '#3B82F6'],
            ['name' => 'En proceso', 'color' => '#F59E0B'],
            ['name' => 'En espera',  'color' => '#8B5CF6'],
            ['name' => 'Resuelto',   'color' => '#10B981'],
            ['name' => 'Cerrado',    'color' => '#6B7280'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
