<?php

namespace Domain\Discounts\Actions;

use Domain\Discounts\Contracts\Repositories\DiscountsRepository;
use Domain\Discounts\Models\Discount;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertDiscount
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Discount
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
            'name' => 'required|unique:discounts,name'
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:discounts';
            $rules['name'] .= ',' . $data['id'] . ',id';
        }

        return $rules;
    }
}
