<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\ProductDiscountsRepository;
use Domain\Products\Models\ProductDiscount;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteProductDiscount
{
    /**
     * @param ProductDiscountsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        protected ProductDiscountsRepository $repository,
        protected Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return ProductDiscount
     * @throws ValidationException
     */
    public function __invoke(array $data): ProductDiscount
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
            'id' => 'required|exists:product_discounts'
        ];
    }
}
