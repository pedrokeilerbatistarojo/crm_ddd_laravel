<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\ProductsRepository;
use Domain\Products\Models\Product;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteProduct
{
    private ProductsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param ProductsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        ProductsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Product
     * @throws ValidationException
     */
    public function __invoke(array $data): Product
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
            'id' => 'required|exists:products'
        ];
    }
}
