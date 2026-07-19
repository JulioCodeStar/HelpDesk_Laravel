<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\Model;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class TicketLogFactory extends Factory
{
    protected $model = Log::class;
    protected static ?array $tickets = null;
    protected static ?array $staff = null;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        self::$tickets ??= Ticket::pluck('id')->all();
        self::$staff   ??= User::whereIn('role', ['agente', 'admin'])->pluck('id')->all();

        return [
            'ticket_id'  => fake()->randomElement(self::$tickets),
            'action'     => fake()->randomElement([
                'Ticket creado',
                'Estado actualizado',
                'Prioridad cambiada',
                'Ticket asignado',
                'Comentario agregado',
                'Ticket cerrado',
                'Ticket reabierto',
            ]),
            'user_id'    => fake()->randomElement(self::$staff),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
