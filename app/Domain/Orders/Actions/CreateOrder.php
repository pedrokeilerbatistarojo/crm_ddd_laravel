<?php

namespace Domain\Orders\Actions;

use Domain\Festives\Contracts\Services\FestivesService;
use Domain\Festives\DataTransferObjects\FestiveSearchRequest;
use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\Enums\OrderType;
use Domain\Orders\Enums\Source;
use Domain\Orders\Models\Order;
use Domain\SaleSessions\Contracts\Services\SaleSessionsService;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Closure;

class CreateOrder
{
    /**
     * @param OrdersRepository $repository
     * @param Factory $validatorFactory
     * @param SaleSessionsService $saleSessionsService
     * @param FestivesService $festivesService
     */
    public function __construct(
        private readonly OrdersRepository $repository,
        private readonly Factory $validatorFactory,
        private readonly SaleSessionsService $saleSessionsService,
        private readonly FestivesService $festivesService
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
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $activeSession = $this->saleSessionsService->activeSession();

        throw_if(!$activeSession, 'RuntimeException', 'Session not opened');

        $data = $this->sanitizeData($data);

        $order = $this->repository->add([
            'sale_session_id' => $activeSession->id,
            ...$data
        ]);

        $order->refresh();

        return $order;
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'client_id' => 'numeric|required_if:type,Cliente',
            'source' => 'required|in:' . implode(',', collect(Source::cases())->pluck('value')->toArray()),
            'total_price' => ['required','numeric', function (string $attribute, mixed $value, Closure $fail) use ($data) {
                $amount = 0;
                foreach ($data['payments'] as $payment) {
                    $amount += $payment['paid_amount'] - $payment['returned_amount'];
                }

                if (round($value, 2) !== round($amount, 2)) {
                    $fail("No coincide el importe total de los pagos con respecto al precio total de la venta. Las devoluciones solo se pueden hacer para pagos en Efectivo.");
                }
            }],
            'type' => 'required|in:' . implode(',', collect(OrderType::cases())->pluck('value')->toArray()),
            'company_id' => 'required|numeric',
            'payments' => 'nullable|array',
            'payments.*.type' => 'required|in:Efectivo,Tarjeta de Crédito,Transferencia',
            'payments.*.amount' => 'required|numeric',
            'payments.*.paid_amount' => 'required|numeric',
            'payments.*.returned_amount' => 'required|numeric',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.product_name' => 'required',
            'details.*.price' => 'required|numeric',
            'details.*.quantity' => 'required|numeric',
            'details.*.circuit_sessions' => 'nullable|numeric',
            'details.*.treatment_sessions' => 'nullable|numeric',
            'details.*.reservations' => 'nullable|array',
            'details.*.reservations.*.type' => 'required|in:Circuit,Treatment',
            'details.*.reservations.*.id' => 'nullable|numeric',
            'details.*.reservations.*.duration' => 'required|numeric',
            'details.*.reservations.*.date' => Rule::forEach(function ($value, $attribute, $data) {
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
            'details.*.reservations.*.time' => 'required|date_format:H:i',
            'details.*.reservations.*.adults' => 'nullable|required_if:details.*.reservations.*.type,Circuit|numeric',
            'details.*.reservations.*.children' => 'nullable|required_if:details.*.reservations.*.type,Circuit|numeric',
            'details.*.reservations.*.employee_id' => 'nullable|numeric',
            'counter_sale_seq' => 'nullable|max:255',
            'note' => 'nullable|max:255',
        ];
    }

    private function sanitizeData(array $data): array
    {
        if (array_key_exists('type', $data)) {
            if ($data['type'] === OrderType::TELEPHONE_SALE->value) {
                $data['client_id'] = config('system.telephone_sale_client_id');
            } else if ($data['type'] === OrderType::COUNTER_SALE->value) {
                $data['client_id'] = config('system.counter_sale_client_id');
            }
        }

        return $data;
    }
}
