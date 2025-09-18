<?php

namespace Database\Factories;

use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DownloadRecord>
 */
class DownloadRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->checkNeededDataExists();

        $downloadable_type = Track::class;
        $downloadable_id = Track::inRandomOrder()->first()?->id;

        return [
            'downloader_id' => User::inRandomOrder()->first()?->id,
            'downloadable_id' => $downloadable_id,
            'downloadable_type' => $downloadable_type,
            'created_at' => $this->faker->dateTime(),
        ];
    }

    private function checkNeededDataExists(){
        if (!Track::count()){
            throw new \Exception('No tracks found!');
        }
        if (!User::count()){
            throw new \Exception('No users found!');
        }
    }
}
