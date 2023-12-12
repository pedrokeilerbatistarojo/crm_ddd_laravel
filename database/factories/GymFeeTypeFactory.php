<?php

namespace Database\Factories;

use Domain\Gyms\Models\GymFeeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GymFeeType>
 */
class GymFeeTypeFactory extends Factory
{
    protected $model = GymFeeType::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 2, 4),
            'period_type' => $this->faker->randomElement(['quincenal','mensual','trimestral','semestral','anual']),
            'payment_day' => $this->faker->numberBetween(0, 31),
            'hour_from' => $this->faker->randomElement(['08:00','09:00','10:00']),
            'hour_to' =>$this->faker->randomElement(['20:00','21:00','22:00']),
            'monday_access' =>$this->faker->boolean(),
            'tuesday_access' =>$this->faker->boolean(),
            'wednesday_access' =>$this->faker->boolean(),
            'thursday_access' =>$this->faker->boolean(),
            'friday_access' =>$this->faker->boolean(),
            'saturday_access' =>$this->faker->boolean(),
            'sunday_access' =>$this->faker->boolean(),
            'unlimited_access' =>$this->faker->boolean()
        ];
    }
}
