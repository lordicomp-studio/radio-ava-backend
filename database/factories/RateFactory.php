<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rate>
 */
class RateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->checkNeededDataExists();

        $ratable_type = rand(0, 1) ? Product::class : Article::class;

        $ratable_id = $ratable_type === Product::class ?
            rand(1, Product::count()) : rand(1, Article::count());

        return [
            'user_id' =>  User::inRandomOrder()->first()?->id,
            'rate' => rand(1, 5),
            'ratable_id' => $ratable_id,
            'ratable_type' => $ratable_type,
            'created_at' => $this->faker->dateTime(),
        ];
    }

    private function checkNeededDataExists(){
        if (!User::count()){
            throw new InvalidArgumentException('No users found!');
        }
        if (!Product::count()){
            throw new InvalidArgumentException('No products found!');
        }
        if (!Article::count()){
            throw new InvalidArgumentException('No articles found!');
        }
    }
}
