<?php

namespace Database\Factories;

use App\Models\Medium;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
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
            'creator_id' => User::inRandomOrder()->first()?->id,
            'cover_id' => Medium::inRandomOrder()->first()?->id,
            'title' => $this->faker->text(50),
            'slug' => $this->faker->slug,
            'description' => $this->faker->text(500),
            'body' => $this->faker->randomHtml,
            'published_at' => $this->faker->dateTime(),
            'status' => rand(0, 1),
            'created_at' => $this->faker->dateTime(),
        ];
    }

    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => true,
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => false,
            ];
        });
    }
}
