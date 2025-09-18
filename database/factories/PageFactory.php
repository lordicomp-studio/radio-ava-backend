<?php

namespace Database\Factories;

use App\Models\Medium;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
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
            'name' => $this->faker->text(50),
            'slug' => $this->faker->slug,
            'creator_id' => User::inRandomOrder()->first()?->id,
            'medium_id' => Medium::inRandomOrder()->first()?->id,
            'description' => $this->faker->randomHtml,
            'created_at' => $this->faker->dateTime(),
        ];
    }

}
