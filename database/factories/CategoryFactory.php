<?php

namespace Database\Factories;

use Domain\Products\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
