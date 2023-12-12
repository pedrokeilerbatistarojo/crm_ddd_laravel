<?php

namespace Database\Factories;

use Domain\Clients\Models\Client;
use Domain\Clients\Models\ClientFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientFile>
 */
class ClientFileFactory extends Factory
{
    protected $model = ClientFile::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $client = Client::all()->random();

        return [
            'client_id' => $client->id,
            'file' => $this->faker->lexify('path/to/file/????????????????????.???'),
            'description' => $this->faker->text(25)
        ];
    }
}
