<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subscription;
use App\Enums\SubscriptionStatus;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // array_rand($types, 1)
        $types = SubscriptionStatus::getValues();
        $status = $types[array_rand($types, 1)];
        return [
            //
            'user_id' => 2,
            'plan_id' => rand(1,3),
            'amount' => $this->faker->numberBetween(198,5000),
            'status' => $status,
        ];
    }
}
