<?php

namespace Database\Factories;

use Domain\Employees\Models\Employee;
use Faker\Provider\es_ES\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Person($this->faker));

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'second_last_name' => $this->faker->lastName(),
            'active' => $this->faker->boolean()
        ];
    }
}
