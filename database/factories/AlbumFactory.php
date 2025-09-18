<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\Medium;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->checkNeededDataExists();

        return [
            'creator_id' => User::inRandomOrder()->first()->id,
            'artist_id' => Artist::inRandomOrder()->first()->id,
            'photo_id' => Medium::inRandomOrder()->first()->id,
            'name' => $this->faker->text(10),
            'release_date' => $this->faker->date(),
        ];
    }

    private function checkNeededDataExists(){
        if (!User::count())
            throw new \Exception('No users found!');
        if (!Artist::count())
            throw new \Exception('No artists found!');
        if (!Medium::count())
            throw new \Exception('No media found!');
    }

}
