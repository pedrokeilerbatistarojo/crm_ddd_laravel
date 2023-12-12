<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\ProductTypesRepository;
use Domain\Products\Models\ProductType;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Closure;

class DeleteProductType
{
    /**
     * @param ProductTypesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        protected ProductTypesRepository $repository,
        protected Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return ProductType
     * @throws ValidationException
     */
    public function __invoke(array $data): ProductType
    {
        $data['products'] = $this->validProductsExist($data);

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
            'id' => 'required|exists:product_types',
            'products' => [
                'bool',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value) {
                        $fail('El tipo de producto que desea eliminar contiene productos asociados.');
                    }
                }
            ]
        ];
    }

    /**
     * @param $data
     * @return bool
     */
    private function validProductsExist($data): bool
    {
        $productTypeEntity = $this->repository->find($data['id']);
        return $productTypeEntity->products->count() > 0;
    }
}
