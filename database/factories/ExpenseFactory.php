<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'unique_code' => \Illuminate\Support\Str::random(10),
            'user_id' => \App\Models\User::pluck('id')->random(),
            'society_id' => \App\Models\Society::pluck('id')->random(),
            'block_id' => \App\Models\Block::pluck('id')->random(),
            'plot_id' => \App\Models\Plot::pluck('id')->random(),
            'flat_id' => \App\Models\Flat::pluck('id')->random(),
            'type' => $this->faker->randomElement(['1','2','3','4','5']),
            'date' => $this->faker->dateTimeThisMonth(),
            'year' => date('Y'),
            'month' => date('m'),
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'description' => $this->faker->sentence(),
            'payment_status' => $this->faker->randomElement(['1','2','3']),
            'status' => '1',
        ];
    }
}
