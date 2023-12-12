<?php

namespace Domain\Orders\Actions;

use Domain\Orders\Contracts\Repositories\OrderDetailsRepository;
use Domain\Orders\Models\OrderDetail;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteOrderDetail
{
    private OrderDetailsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param OrderDetailsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        OrderDetailsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return OrderDetail
     * @throws ValidationException
     */
    public function __invoke(array $data): OrderDetail
    {
        $validator = $this->validatorFactory->make($data, $this->rules());

        $validator->validate();

        return $this->repository->delete($data);
    }

    /**
     * @return array
     */
    private function rules(): array
    {
        return [
            'id' => 'required|exists:order_details,id'
        ];
    }
}
