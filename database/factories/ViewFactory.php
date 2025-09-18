<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Article;
use App\Models\Playlist;
use App\Models\Product;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\View>
 */
class ViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->checkNeededDataExists();

        $viewables = [Article::class, Album::class, Track::class, Playlist::class];
        $viewable = $viewables[rand(0, count($viewables) - 1)];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'ip' => $this->faker->ipv4,
            'device' => $this->faker->text(10),
            'platform' => $this->faker->text(10),
            'browser' => $this->faker->text(10),
            'viewable_type' => $viewable,
            'viewable_id' => $viewable::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTime(),
        ];
    }

    private function checkNeededDataExists(){
        if (!User::count())
            throw new \Exception('No users found!');
        if (!Track::count())
            throw new \Exception('No tracks found!');
        if (!Album::count())
            throw new \Exception('No albums found!');
        if (!Playlist::count())
            throw new \Exception('No playlists found!');
        if (!Article::count())
            throw new \Exception('No articles found!');
    }
}
