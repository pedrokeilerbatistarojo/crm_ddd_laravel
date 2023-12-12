<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymFeeTypesRepository;
use Domain\Gyms\Enums\GymFeeTypePeriodType;
use Domain\Gyms\Models\GymFeeType;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertGymFeeType
{
    private GymFeeTypesRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param GymFeeTypesRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        GymFeeTypesRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return GymFeeType
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): GymFeeType
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
            'name' => 'required',
            'price' => 'required|numeric',
            'period_type' => 'required|in:' . implode(',', collect(GymFeeTypePeriodType::cases())->pluck('value')->toArray()),
            'payment_day' => 'nullable|numeric',
            'duration_number_of_days' => 'required|numeric',
            'hour_from' => 'required',
            'hour_to' => 'required',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:gym_fee_types';
        }

        return $rules;
    }
}
