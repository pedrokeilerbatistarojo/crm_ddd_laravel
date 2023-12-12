<?php

namespace Database\Factories;

use Domain\Products\Models\Product;
use Domain\Products\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'product_type_id' => ProductType::Factory(),
            'name' => $this->faker->word(),
            'short_description' => $this->faker->text(),
            'description' => $this->faker->realText(),
            'price' => $this->faker->randomFloat(2, 2, 4),
            'price_type' => 'Fijo',
            'priority' => 1,
            'all_reserves_on_same_day' => $this->faker->boolean()
        ];
    }
}
