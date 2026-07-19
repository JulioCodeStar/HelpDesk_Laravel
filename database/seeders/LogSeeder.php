<?php

namespace Database\Seeders;

use App\Models\Log;
use Database\Factories\TicketLogFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::factory()->count(500)->create();
    }
}
