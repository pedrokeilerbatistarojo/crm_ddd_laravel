<?php

namespace Database\Factories;

use Domain\Orders\Models\Order;
use Domain\Products\Models\Product;
use Domain\Orders\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    protected $model = OrderDetail::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $order = Order::all()->random();
        $product = Product::all()->random();
        $quantity = $this->faker->randomDigitNot(0);

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'price' => $product->price,
            'circuit_sessions' => 1,
            'treatment_sessions' => 1,
        ];
    }
}
