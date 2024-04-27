<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'tags' => 'Minuman, Makanan',
            'product_points' => $this->faker->numberBetween(10,100),
            'description' => $this->faker->paragraph(3),
            'location' => $this->faker->city(),
        ];
    }
}
