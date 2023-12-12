<?php

namespace Database\Factories;

use Domain\Clients\Models\Client;
use Domain\Clients\Models\ClientNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientNote>
 */
class ClientNoteFactory extends Factory
{
    protected $model = ClientNote::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $client = Client::all()->random();
        $note = $this->faker->realText();

        return [
            'client_id' => $client->id,
            'note' => $note
        ];
    }
}
