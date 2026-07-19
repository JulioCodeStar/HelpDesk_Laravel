<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketMessage>
 */
class TicketMessageFactory extends Factory
{
    protected static ?array $tickets = null;
    protected static ?array $users = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        self::$tickets ??= Ticket::pluck('id')->all();
        self::$users   ??= User::pluck('id')->all();

        $createdAt = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'ticket_id'  => fake()->randomElement(self::$tickets),
            'user_id'    => fake()->randomElement(self::$users),
            'message'    => fake()->paragraph(),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
