<?php

namespace Domain\Orders\Actions;

use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\Enums\Source;
use Domain\Orders\Enums\OrderType;
use Domain\Orders\Models\Order;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class UpdateOrder
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

        return $this->repository->edit($data);
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:orders,id',
            'client_id' => 'required|numeric',
            'company_id' => 'required|numeric',
            'source' => 'required|in:' . implode(',', collect(Source::cases())->pluck('value')->toArray()),
            'total_price' => 'required|numeric',
            'type' => 'required|in:' . implode(',', collect(OrderType::cases())->pluck('value')->toArray()),
            'counter_sale_seq' => 'nullable|max:255',
            'note' => 'nullable|max:255',
        ];
    }
}
