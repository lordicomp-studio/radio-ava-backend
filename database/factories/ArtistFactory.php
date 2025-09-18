<?php

namespace Database\Factories;

use App\Models\Medium;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name;

        return [
            'photo_id' => Medium::inRandomOrder()->first()->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'birth_date' => $this->faker->date(),
        ];
    }

    private function checkNeededDataExists(){
        if (!Medium::count())
            throw new \Exception('No media found!');
    }

}
