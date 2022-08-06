<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(7),
            'quantity' => $this->faker->numberBetween(1,10),
            'status' => $this->faker->randomElement([Product::UNAVAILABLE_PRODUCT, Product::AVAILABLE_PRODUCT]),
            'image' =>  $this->faker->randomElement(['1.jpg','2.jpg','3.jpg']), 
            'seller_id' => User::all()->random()->id
            // User::inRandomOrder()->first()->id
        ];
    }
}
