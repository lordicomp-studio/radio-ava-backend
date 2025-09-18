<?php

namespace Database\Factories;

use App\Enums\AvailableCurrenciesEnum;
use App\Enums\PaymentStatusEnum;
use App\Helpers\CodeGenerator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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

        $data = [
            'payer_id' => User::inRandomOrder()->first()?->id,
            'track_id' => '',
            'code' => CodeGenerator::makeUniquePaymentCode(),
            'price' => rand(1, 999) . '000',
            'status' => PaymentStatusEnum::cases()[rand(0, 2)],
            'gateway' => ['Paypal', 'Crypto'][rand(0, 1)],
            'created_at' => $this->faker->dateTime(),
        ];

        if ($data['gateway'] === 'Crypto'){
            $data['chosen_currency'] = AvailableCurrenciesEnum::cases()[rand(0, 1)]->value;
            $data['chosen_currency_price'] = rand(1, 1000) * (10**18);
        }

        return $data;
    }
}
