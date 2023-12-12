<?php

namespace Database\Factories;

use Domain\Orders\Models\Order;
use Domain\Clients\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $client = Client::all()->random();

        return [
            'client_id' => $client->id,
            'locator' => uniqid('', false),
            'source' => $this->faker->randomElement([
                'CRM',
                'Web',
            ]),
            'total_price' => $this->faker->randomFloat(2, 3, 1000),
            'company_id' => 1,
            'discount' => 'prueba'
        ];
    }
}
