<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['offen', 'erledigt']),
            'category_type' => $this->faker->randomElement(['WorkTask', 'PersonalTask', 'ShoppingTask', null]),
            'extra_data' => function (array $attributes) {
                if ($attributes['category_type'] === 'WorkTask') {
                    return json_encode(['priority' => $this->faker->randomElement(['low', 'medium', 'high'])]);
                } elseif ($attributes['category_type'] === 'PersonalTask') {
                    return json_encode(['mood' => $this->faker->randomElement(['happy', 'sad', 'neutral'])]);
                } elseif ($attributes['category_type'] === 'ShoppingTask') {
                    return json_encode(['estimated_cost' => $this->faker->randomFloat(2, 10, 500)]);
                }
                return null;
            },
        ];
    }
}
