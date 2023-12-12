<?php

namespace Domain\Orders\Actions;

use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Domain\Festives\Contracts\Services\FestivesService;
use Domain\Festives\DataTransferObjects\FestiveSearchRequest;
use Domain\Orders\Contracts\Repositories\OrderDetailsRepository;
use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\Models\OrderDetail;
use Domain\Products\Services\ProductsService;
use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CreateOrderDetail
{
    /**
     * @param OrderDetailsRepository $repository
     * @param OrdersRepository $ordersRepository
     * @param CircuitReservationsService $circuitReservationsService
     * @param TreatmentReservationsService $treatmentReservationsService
     * @param ProductsService $productsService
     * @param Factory $validatorFactory
     */
    public function __construct(
        private readonly OrderDetailsRepository $repository,
        private readonly OrdersRepository $ordersRepository,
        private readonly CircuitReservationsService $circuitReservationsService,
        private readonly TreatmentReservationsService $treatmentReservationsService,
        private readonly ProductsService $productsService,
        private readonly FestivesService $festivesService,
        private readonly Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return OrderDetail
     * @throws ValidationException
     * @throws UnknownProperties
     */
    public function __invoke(array $data): OrderDetail
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        if (!$record = $this->repository->add($data)) {
            throw new \RuntimeException('Order detail can`t be saved.');
        }

        if (!empty($data['reservations'])) {
            if (!$order = $this->ordersRepository->find($data['order_id'])) {
                throw new \RuntimeException('Order not found');
            }
            if (!$product = $this->productsService->find($data['product_id'], ['productType'])) {
                throw new \RuntimeException('Product not found');
            }
            foreach ($data['reservations'] as $reservation) {
                $reservationData = [
                    'client_id' => $order->client_id,
                    'order_detail_id' => $record->id,
                    'date' => $reservation['date'],
                    'time' => $reservation['time'],
                    'duration' => $reservation['duration'],
                    'used' => 0
                ];
                if (empty($reservation['id'])) {
                    $reservationAction = 'create';
                } else {
                    $reservationAction = 'update';
                    $reservationData = ['id' => $reservation['id'], ...$reservationData];
                }
                if ($reservation['type'] === 'Circuit') {
                    $this->circuitReservationsService->$reservationAction([
                        'adults' => $reservation['adults'],
                        'children' => $reservation['children'],
                        ...$reservationData
                    ]);
                } elseif ($reservation['type'] === 'Treatment') {
                    $this->treatmentReservationsService->$reservationAction([
                        'employee_id' => $reservation['employee_id'] ?? null,
                        ...$reservationData
                    ]);
                }
            }
        }

        return $record;
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'circuit_sessions' => 'nullable|numeric',
            'treatment_sessions' => 'nullable|numeric',
            'reservations' => 'nullable|array',
            'reservations.*.type' => 'required|in:Circuit,Treatment',
            'reservations.*.id' => 'nullable|numeric',
            'reservations.*.duration' => 'required|numeric',
            'reservations.*.date' => Rule::forEach(function ($value, $attribute, $data) {
                $festiveRecords = $this->festivesService->search(
                    new FestiveSearchRequest([
                        'filters' => ['date' => $value],
                        'paginateSize' => config('system.infinite_pagination')
                    ])
                )->getData();
                $time = $data[str_replace('.date', '.time', $attribute)];
                return [
                    'required',
                    'date_format:Y-m-d',
                    function ($attribute, $value, $fail) use ($festiveRecords, $time) {
                        if ($festiveRecords->count()) {
                            foreach ($festiveRecords as $festive) {
                                if (
                                    $festive->type === 'Día Completo'
                                    ||
                                    in_array($time, $festive->closed_hours)
                                ) {
                                    $fail('Date and time not available to reserve.');
                                }
                            }
                        }
                    },
                ];
            }),
            'reservations.*.time' => 'required|date_format:H:i',
            'reservations.*.adults' => 'nullable|required_if:reservations.*.type,Circuit|numeric',
            'reservations.*.children' => 'nullable|required_if:reservations.*.type,Circuit|numeric',
            'reservations.*.employee_id' => 'nullable|numeric'
        ];
    }
}
