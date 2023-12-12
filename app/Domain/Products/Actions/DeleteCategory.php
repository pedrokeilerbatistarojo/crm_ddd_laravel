<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\CategoriesRepository;
use Domain\Products\Models\Category;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Closure;

class DeleteCategory
{
    /**
     * @param CategoriesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        protected CategoriesRepository $repository,
        protected Factory $validatorFactory
    ) {
    }

    /**
     * @param array $data
     * @return Category
     * @throws ValidationException
     */
    public function __invoke(array $data): Category
    {
        $data['productTypes'] = $this->validProductTypesExist($data);
        
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
            'id' => 'required|exists:categories',
            'productTypes' => [
                'bool',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value) $fail('La categoría que desea eliminar contiene tipos de producto asociados.');
                }
            ]
        ];
    }

    /**
     * @param $data
     * @return bool
     */
    private function validProductTypesExist($data): bool
    {
        $categoryEntity = $this->repository->find($data['id']);
        return $categoryEntity->productTypes->count() > 0;
    }
}
