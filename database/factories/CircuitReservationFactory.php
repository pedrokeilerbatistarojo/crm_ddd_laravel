<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Domain\Orders\Models\Order;
use Domain\Clients\Models\Client;
use Illuminate\Support\Facades\DB;
use Domain\Orders\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Domain\CircuitReservations\Models\CircuitReservation;

/**
 * @extends Factory<CircuitReservation>
 */
class CircuitReservationFactory extends Factory
{
    protected $model = CircuitReservation::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $reservationsDaysAfterNow = $this->faker->randomDigitNotNull();
        $hourRanges = [
            [
                '9:30',
                '180',
            ],
            [
                '10:00',
                '180',
            ],
            [
                '10:30',
                '180',
            ],
            [
                '11:00',
                '180',
            ],
            [
                '12:00',
                '180',
            ],
        ];
        $selectedRange = $hourRanges[$this->faker->numberBetween(0, count($hourRanges) - 1)];
        $date = Carbon::now()->addDays($reservationsDaysAfterNow);
        $client = Client::all()->random();

        return [
            'client_id' => $client->id,
            'date' => $date,
            'time' => $selectedRange[0],
            'duration' => $selectedRange[1],
            'adults' => $this->faker->randomElement([
                '1',
                '2',
            ]),
            'children' => $this->faker->randomElement([
                '1',
                '2',
            ]),
            'used' => $this->faker->boolean(),
            'created_by' => 1,
            'last_modified_by' => 1,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (CircuitReservation $circuitReservation) {
            //
        })->afterCreating(function (CircuitReservation $circuitReservation) {
            $order = Order::create(
                [
                    'client_id' => $circuitReservation->client_id,
                    'locator' => uniqid('', false),
                    'source' => 'CRM',
                    'total_price' => '37',
                    'company_id' => 1,
                    'discount' => 'prueba'
                ]
            );

            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => 2,
                'product_name' => 'ENTRADA GENERAL',
                'quantity' => 1,
                'price' => 37,
                'circuit_sessions' => 1,
                'treatment_sessions' => 1,
            ]);

            DB::unprepared("
                INSERT INTO circuit_reservations_order_details (id, order_detail_id) VALUES ($circuitReservation->id, $orderDetail->id)
            ");
        });
    }
}
