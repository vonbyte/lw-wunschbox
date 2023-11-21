<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wish>
 */
class WishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'description' => fake()->realText(30),
            'image' => fake()->imageUrl,
            'links' => fake()->url() . ", " . fake()->url(),
            'admin_notes' => fake()->paragraph,
            'sortnr' => null,
            'state' => 1,
            'shipping_state' => null,
            'shipping_company' => null,
            'trackingnumber' => null,
            'delivery_date' => null
        ];
    }
}
