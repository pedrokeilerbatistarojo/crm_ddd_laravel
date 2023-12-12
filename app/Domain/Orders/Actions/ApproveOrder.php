<?php

namespace Domain\Orders\Actions;

use Carbon\Carbon;
use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Domain\Clients\Models\Client;
use Domain\Orders\Contracts\Repositories\OrdersApprovalRepository;
use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\Models\Order;
use Domain\Products\Contracts\Services\ProductsService;
use Domain\SaleSessions\Contracts\Services\SaleSessionsService;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class ApproveOrder
{
    /**
     * @param OrdersApprovalRepository $ordersApprovalRepository
     * @param OrdersRepository $repository
     * @param SaleSessionsService $saleSessionsService
     * @param CircuitReservationsService $circuitReservationsService
     * @param ProductsService $productsService
     * @param Factory $validatorFactory
     */
    public function __construct(
        protected OrdersApprovalRepository $ordersApprovalRepository,
        protected OrdersRepository $repository,
        protected SaleSessionsService $saleSessionsService,
        protected CircuitReservationsService $circuitReservationsService,
        protected ProductsService $productsService,
        protected Factory $validatorFactory,
    ) {
    }

    /**
     * @param array $data
     * @return Order
     * @throws ValidationException
     * @throws \Throwable
     */
    public function __invoke(array $data): Order
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        $record = $this->ordersApprovalRepository->find($data['id']);

        $client = $this->getClient($record->order_data);

        $order = [
            'locator' => $record->order_data['beeper'],
            'source' => 'Web',
            'client_id' => $client->id,
            'type' => 'Cliente',
            'company_id' => '1',
            'total_price' => $record->order_data['total_price'],
            'payments' => [
                [
                    'type' => 'Transferencia',
                    'amount' => $record->order_data['total_price'],
                    'paid_amount' => $record->order_data['total_price'],
                    'returned_amount' => 0,
                ]
            ]
        ];

        $circuitReservations = [];
        if (array_key_exists('details', $record->order_data)) {
            foreach ($record->order_data['details'] as $detail) {
                $product = $this->productsService->findByName($detail['name']);
                throw_if(!$product, 'RuntimeException', 'Product ' . $detail['name'] . ' not found');

                if (array_key_exists('options', $detail) && is_array($detail['options'])) {
                    if (in_array($detail['sku'], ['100100', '100101'], true)) {
                        if (empty($circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']])) {
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['adults'] = 0;
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['children'] = 0;
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['date'] = Carbon::createFromFormat(
                                'd/m/Y',
                                $detail['options']['date']
                            )->format('Y-m-d');
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['time'] = $detail['options']['time'];
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['duration'] = 180;
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['userd'] = !$detail['left'];
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['details'] = [];
                        }

                        if ($detail['sku'] === '100100') {
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['adults'] += $detail['qty'];
                        }

                        if ($detail['sku'] === '100101') {
                            $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']]['children'] += $detail['qty'];
                        }
                    }
                }
            }
        }

        $orderDetails = [];
        if (array_key_exists('details', $record->order_data)) {
            foreach ($record->order_data['details'] as $detail) {
                $product = $this->productsService->findByName($detail['name']);
                throw_if(!$product, 'RuntimeException', 'Product ' . $detail['name'] . ' not found');
                $reservations = [];
                if (
                    array_key_exists('options', $detail) &&
                    is_array($detail['options']) &&
                    array_key_exists('date', $detail['options']) &&
                    array_key_exists('time', $detail['options']) &&
                    $product->name !== 'Circuito Termal (Niño)'
                ) {
                    $reservation = $circuitReservations[$detail['options']['date'] . ' ' . $detail['options']['time']];
                    if (!empty($reservation)) {
                        $reservations[] = [
                            'type' => 'Circuit',
                            'duration' => $reservation['duration'],
                            'date' => $reservation['date'],
                            'time' => str_pad($reservation['time'], 5, '0', STR_PAD_LEFT),
                            'adults' => $reservation['adults'],
                            'children' => $reservation['children'],
                        ];
                    }
                }

                $orderDetails[] = [
                    'product_id' => $product->id,
                    'product_name' => $detail['name'],
                    'price' => $detail['price'],
                    'quantity' => $detail['qty'],
                    'circuit_sessions' => $product->circuit_sessions,
                    'treatment_sessions' => $product->treatment_sessions,
                    'reservations' => $reservations
                ];
            }
        }

        $order['details'] = $orderDetails;

        $order = app(CreateOrder::class)($order);

        app(DeleteOrderApproval::class)($data);

        return $order;
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:orders_approval'
        ];
    }

    /**
     * @param $data
     * @return Client
     */
    private function getClient($data): Client
    {
        if (!$client = Client::query()->where('email', $data['customer']['email'])->first()) {
            $client = new Client(['email' => $data['customer']['email']]);
        }
        $client->name = $data['customer']['fullname'];
        $client->phone = array_key_exists('phone', $data['customer']) ? substr(
            $data['customer']['phone'],
            0,
            20
        ) : null;
        $client->birthdate = $data['customer']['birthdate'] ?? null;
        $client->opt_in = $data['customer']['allow_receive_emails'];
        $client->address = $data['customer']['address'] ?? null;
        $client->postcode = array_key_exists('postal_code', $data['customer']) ? substr(
            $data['customer']['postal_code'],
            0,
            5
        ) : null;
        $client->save();

        return $client;
    }

}
