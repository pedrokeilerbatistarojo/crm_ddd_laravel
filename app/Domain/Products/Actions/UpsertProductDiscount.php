<?php

namespace Domain\Products\Actions;

use Domain\Products\Contracts\Repositories\ProductDiscountsRepository;
use Domain\Products\Models\ProductDiscount;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpsertProductDiscount
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
            'product_id' => 'required|exists:products,id',
            'discount_id' => [
                'required',
                Rule::unique('product_discounts')->where(function ($query) use($data) {
                    return $query->where('product_id', $data['product_id'] ?? null)
                        ->where('discount_id', $data['discount_id'] ?? null);
                })->ignore($data['id'] ?? null)
            ],
            'price' => 'required|numeric',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:product_discounts';
        }

        return $rules;
    }
}
