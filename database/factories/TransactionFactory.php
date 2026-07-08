<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\TransactionService;
use App\Enums\TransactionType;
use App\Models\Transaction;

class TransactionFactory extends Factory
{

    protected $model = Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amount = $this->faker->numberBetween(198,5000);
        $types = TransactionType::getValues();
        $type = $types[array_rand($types, 1)];
        return [
            'payment_id' => TransactionService::id(),
            'user_id' => 2,
            'account_id' => 1,
            'price_amount' => $amount,
            'pay_amount' => $amount,
            'price_currency' => 'USD',
            'pay_currency' => 'USD',
            'order_id' => NULL,
            'order_description' => '',
            'sub_id' => NULL,
            'is_debit' => (TransactionType::fromString($type) == TransactionType::WALLET_DEPOSIT) ? false : true,
            'type' => $type,
            'payment_status' => (TransactionType::fromString($type) == TransactionType::WALLET_DEPOSIT) ? 'waiting' : 'finished',
            'pay_address' => NULL,
            'network' => NULL,
            'expiration_estimate_date' => $this->faker->dateTimeBetween('now', '+2 days')->format('Y-m-d')
        ];
    }
}
