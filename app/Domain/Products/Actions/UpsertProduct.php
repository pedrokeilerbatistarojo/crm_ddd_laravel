<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\ProductsRepository;
use Domain\Products\Enums\PriceType;
use Domain\Products\Models\Product;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertProduct
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Product
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $method = array_key_exists('id', $data) ? 'edit' : 'add';

        return $this->repository->$method($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        $rules = [
            'product_type_id' => 'nullable|numeric|exists:product_types,id',
            'priority' => 'required',
            'price' => 'required|numeric',
            'price_type' => 'required|in:' . implode(',', collect(PriceType::cases())->pluck('value')->toArray()),
            'circuit_sessions' => 'required|numeric',
            'treatment_sessions' => 'required|numeric',
            'online_sale' => 'required|boolean',
            'editable' => 'required|boolean',
            'available' => 'required|boolean',
            'name' => 'required',
            'all_reserves_on_same_day' => 'required|boolean',
            'duration_circuit_schedule' => Rule::requiredIf(fn () => (array_key_exists('circuit_sessions', $data) && $data['circuit_sessions'] > 0)),
            'duration_treatment_schedule' => Rule::requiredIf(fn () => (array_key_exists('treatment_sessions', $data) && $data['treatment_sessions'] > 0)),
            'background_color' => ['nullable', 'regex:/^(\#[\da-f]{3}|\#[\da-f]{6})$/i'],
            'text_color' => ['nullable', 'regex:/^(\#[\da-f]{3}|\#[\da-f]{6})$/i']
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:products';
            $rules['background_color'][0] = 'required';
            $rules['text_color'][0] = 'required';
        }

        return $rules;
    }
}
