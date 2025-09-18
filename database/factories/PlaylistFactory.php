<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Playlist>
 */
class PlaylistFactory extends Factory
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
            'name' => $this->faker->text(15),
        ];
    }

    private function checkNeededDataExists(){
        if (!User::count())
            throw new \Exception('No users found!');
    }

}
