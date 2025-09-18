<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
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
            'user_id' => rand(1, User::count()),
            'created_at' => $this->faker->dateTime(),
        ];
    }
}
