<?php

namespace Database\Factories;

use App\Enums\UserLevelEnum;
use App\Models\Medium;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $random = rand(0, 1);

        return [
            'name' => $this->faker->name(),
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'bio' => $this->faker->text(400),
            'level' => UserLevelEnum::cases()[rand(0, 2)],
            'email_verified_at' => $random? now() : null,
            'profile_photo_id' => Medium::inRandomOrder()->first()?->id,
            'password' => Hash::make('123456789'), // password
            'created_at' => $this->faker->dateTime(),
        ];
    }

    public function admin(){
        return $this->state(function (array $attributes) {
            return [
                'level' => UserLevelEnum::Admin,
            ];
        });
    }

    public function seller(){
        return $this->state(function (array $attributes) {
            return [
                'level' => UserLevelEnum::Seller,
            ];
        });
    }

    public function client(){
        return $this->state(function (array $attributes) {
            return [
                'level' => UserLevelEnum::Client,
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
