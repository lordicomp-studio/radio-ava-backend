<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (!Chat::count())
            throw new InvalidArgumentException('No chats found!');

        $chat = Chat::inRandomOrder()->first();
        $replyToMessage = Message::inRandomOrder()->first();

        $senderId = rand(0, 1) ? $chat->sender_id : $chat->receiver_id;

        return [
            'chat_id' => $chat->id,
            'sender_id' => $senderId,
            'message' => $this->faker->text,
            'file_url' => rand(0, 1) ? $this->faker->url : null,
            'seen_at' => rand(0, 1) ? $this->faker->dateTime : null,
            'created_at' => $this->faker->dateTime(),
            'reply_to_id' => $replyToMessage?->id,
        ];
    }

    public function unseen(){
        return $this->state(function (array $attributes) {
            return [
                'seen_at' => null,
            ];
        });
    }

    public function seen(){
        return $this->state(function (array $attributes) {
            return [
                'seen_at' => Carbon::now(),
            ];
        });
    }

}
