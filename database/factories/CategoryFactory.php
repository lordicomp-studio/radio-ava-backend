<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if (!User::count()){
            throw new InvalidArgumentException('No users found!');
        }

        return [
            'name' => $this->faker->text(20),
            'user_id' => User::inRandomOrder()->first()?->id,
            'created_at' => $this->faker->dateTime(),
        ];
    }
}
