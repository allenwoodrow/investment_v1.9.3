<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = \App\Models\Plan::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = [
            "weeks", "months","days"
        ];
        $type = $types[array_rand($types, 1)];
        return [
            //
            'name' => $this->faker->words(1, true),
            'description' => $this->faker->words(3, true), 
            'investment_term' => rand(1, 12) . " " .$type,
            'min_amount' => rand(100, 100000),
            'active' => true
        ];
    }
}
