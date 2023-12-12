<?php

namespace Domain\Orders\Actions;

use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\Models\Order;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditNoteOrder
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

        return $this->repository->edit(
            Arr::only($data, ['id', 'note'])
        );
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:orders,id',
            'note' => 'required|max:255',
        ];
    }
}
