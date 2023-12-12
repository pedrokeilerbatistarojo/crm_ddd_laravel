<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\CategoriesRepository;
use Domain\Products\Models\Category;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class UpsertCategory
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
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $method = array_key_exists('id', $data) ? 'edit' : 'add';

        $record = $this->repository->$method($data);

        return $record->fresh();
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        $rules = [
            'name' => 'required|unique:categories,name',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:categories';
            $rules['name'] .= ',' . $data['id'] . ',id';
        }

        return $rules;
    }
}
