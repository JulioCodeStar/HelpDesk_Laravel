<?php

namespace Database\Seeders;

use App\Models\TicketMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketMessage::factory()->count(3000)->create();
    }
}
