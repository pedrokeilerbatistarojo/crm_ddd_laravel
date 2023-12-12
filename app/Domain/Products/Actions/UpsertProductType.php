<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\ProductTypesRepository;
use Domain\Products\Models\ProductType;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class UpsertProductType
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
            'category_id' => 'required',
            'name' => 'required',
            'priority' => 'required|numeric',
            'background_color' => ['nullable', 'regex:/^(\#[\da-f]{3}|\#[\da-f]{6})$/i'],
            'text_color' => ['nullable', 'regex:/^(\#[\da-f]{3}|\#[\da-f]{6})$/i']
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:product_types';
            $rules['background_color'][0] = 'required';
            $rules['text_color'][0] = 'required';
        }

        return $rules;
    }
}
