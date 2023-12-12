<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymFeeTypesRepository;
use Domain\Gyms\Models\GymFeeType;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteGymFeeType
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
     * @throws ValidationException
     */
    public function __invoke(array $data): GymFeeType
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
            'id' => 'required|exists:gym_fee_types'
        ];
    }
}
