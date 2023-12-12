<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Domain\Invoices\Models\Invoice;
use Domain\Clients\Models\Client;
use Faker\Provider\es_ES\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $client = Client::all()->random();
        $daysAfterNow = $this->faker->randomDigitNotNull() * -1;
        $date = Carbon::now()->addDays($daysAfterNow);

        return [
            'client_id' => $client->id,
            'number' =>uniqid('', false),
            'description' => $this->faker->realtext(),
            'invoice_type' => $this->faker->randomElement(['Cuota','Order','Custom']),
            'invoice_date' => $date,
            'address' => $this->faker->address(),
            'zip_code' => $this->faker->postcode(),
            'locality' => $this->faker->city(),
            'province' => $this->faker->state(),
            'observations' => $this->faker->text(),
        ];
    }
}
