<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medium>
 */
class MediumFactory extends Factory
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

        $formats = ['jpg', 'png', 'mp4', 'mkv'];
        $rand = rand(0, count($formats) - 1);

        return [
            'name' => Str::random(rand(10, 45)) . $formats[$rand],
            'url' => '/laravel-optimization(compressed)-min.jpg',
            'uploader_id' => User::inRandomOrder()->first()?->id,
            'size' => rand(10, 100000),
            'format' => $formats[$rand],
            'created_at' => $this->faker->dateTime(),
        ];
    }
}
