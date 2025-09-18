<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Article;
use App\Models\Artist;
use App\Models\Medium;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->checkNeededDataExists();

        $name = $this->faker->text(10);

        return [
            'uploader_id' => User::inRandomOrder()->first()->id,
            'artist_id' => Artist::inRandomOrder()->first()->id,
            'album_id' => Album::inRandomOrder()->first()->id,
            'cover_id' => Medium::inRandomOrder()->first()->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'duration' => rand(120, 300),
            'release_date' => $this->faker->date,
        ];
    }

    private function checkNeededDataExists(){
        if (!User::count())
            throw new \Exception('No users found!');
        if (!Artist::count())
            throw new \Exception('No artists found!');
        if (!Album::count())
            throw new \Exception('No albums found!');
        if (!Medium::count())
            throw new \Exception('No media found!');
    }
}
