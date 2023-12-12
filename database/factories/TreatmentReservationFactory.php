<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Domain\Orders\Models\Order;
use Domain\Clients\Models\Client;
use Illuminate\Support\Facades\DB;
use Domain\Orders\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Domain\TreatmentReservations\Models\TreatmentReservation;

/**
 * @extends Factory<TreatmentReservation>
 */
class TreatmentReservationFactory extends Factory
{
    protected $model = TreatmentReservation::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $hourRanges = ['9:30', '10:00', '10:30', '11:00', '12:00'];

        return [
            'client_id' => Client::factory(),
            'employee_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'date' => Carbon::now()->addDays($this->faker->randomDigitNotNull()),
            'time' => $hourRanges[$this->faker->numberBetween(0, count($hourRanges) - 1)],
            'duration' => $this->faker->randomElement([30, 45, 60, 90, 120]),
            'used' => $this->faker->boolean(),
            'created_by' => 1,
            'last_modified_by' => 1
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (TreatmentReservation $circuitReservation) {
            //
        })->afterCreating(function (TreatmentReservation $circuitReservation) {
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
                INSERT INTO treatment_reservations_order_details (id, order_detail_id) VALUES ($circuitReservation->id, $orderDetail->id)
            ");
        });
    }
}
