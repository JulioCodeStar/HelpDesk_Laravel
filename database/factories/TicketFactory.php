<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected static ?array $clientes = null;
    protected static ?array $agentes = null;
    protected static ?array $statuses = null;
    protected static ?array $priorities = null;
    protected static ?array $categories = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        self::$clientes   ??= User::where('role', 'cliente')->pluck('id')->all();
        self::$agentes    ??= User::where('role', 'agente')->pluck('id')->all();
        self::$statuses   ??= Status::pluck('id')->all();
        self::$priorities ??= Priority::pluck('id')->all();
        self::$categories ??= Category::pluck('id')->all();

        $createdAt = fake()->dateTimeBetween('-6 months', 'now');
        $cerrado   = fake()->boolean(40); // 40% de los tickets están cerrados

        return [
            'user_id'     => fake()->randomElement(self::$clientes),
            'assigned_to' => fake()->boolean(80)
                ? fake()->randomElement(self::$agentes)
                : null, // 20% sin asignar
            'subject'     => rtrim(fake()->sentence(6), '.'),
            'description' => fake()->paragraphs(2, true),
            'status_id'   => fake()->randomElement(self::$statuses),
            'priority_id' => fake()->randomElement(self::$priorities),
            'category_id' => fake()->randomElement(self::$categories),
            'created_at'  => $createdAt,
            'updated_at'  => $createdAt,
            'closed_at'   => $cerrado ? fake()->dateTimeBetween($createdAt, 'now') : null,
        ];
    }
}
