<?php

namespace Database\Factories;

use Domain\Clients\Models\Client;
use Faker\Provider\es_ES\Address;
use Faker\Provider\es_ES\Person;
use Faker\Provider\es_ES\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new Address($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'document' => $this->faker->dni(),
            'name' => "{$this->faker->firstName()} {$this->faker->lastName()} {$this->faker->lastName()}",
            'phone' => $this->faker->mobileNumber(),
            'birthdate' => $this->faker->date(),
            'address' => $this->faker->address(),
            'postcode' => $this->faker->postcode(),
            'opt_in' => $this->faker->boolean(),
        ];
    }
}
