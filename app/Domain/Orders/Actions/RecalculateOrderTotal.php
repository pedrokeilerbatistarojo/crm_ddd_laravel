<?php

namespace Domain\Orders\Actions;

use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\Models\Order;
use Domain\Orders\Models\OrderDetail;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class RecalculateOrderTotal
{
    private OrdersRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param OrdersRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        OrdersRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Order
     * @throws ValidationException
     */
    public function __invoke(array $data): Order
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        $record = $this->repository->find($data['id']);

        $total = $record->orderDetails->sum(function (OrderDetail $detail) {
            return $detail->price * $detail->quantity;
        });

        return $this->repository->edit(['id' => $record->id, 'total_price' => $total]);
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:orders,id',
        ];
    }
}
