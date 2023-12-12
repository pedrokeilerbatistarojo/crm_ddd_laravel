<?php

namespace Database\Factories;

use Domain\Products\Models\Category;
use Domain\Products\Models\Product;
use Domain\Products\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductTypeFactory extends Factory
{
    protected $model = ProductType::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->word(),
            'priority' => 1,
        ];
    }
}
