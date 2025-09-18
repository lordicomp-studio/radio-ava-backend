<?php

namespace Database\Factories;

use App\Enums\ChatPriorityEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (User::count() < 2){
            throw new InvalidArgumentException('Not enough users found!');
        }

        $randomUsers = User::inRandomOrder()->take(2)->get();

        return [
            'sender_id' => $randomUsers[0]->id,
            'receiver_id' => $randomUsers[1]->id,
            'subject' => $this->faker->text(255),
            'priority' => ChatPriorityEnum::cases()[rand(0, 2)],
            'status' => rand(0, 1),
            'created_at' => $this->faker->dateTime(),
        ];
    }

}
