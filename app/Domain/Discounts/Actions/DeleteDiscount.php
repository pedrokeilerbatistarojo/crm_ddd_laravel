<?php

namespace Domain\Discounts\Actions;

use Domain\Discounts\Contracts\Repositories\DiscountsRepository;
use Domain\Discounts\Models\Discount;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteDiscount
{
    private DiscountsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param DiscountsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        DiscountsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Discount
     * @throws ValidationException
     */
    public function __invoke(array $data): Discount
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        return $this->repository->delete($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'id' => 'required|exists:discounts'
        ];
    }
}
