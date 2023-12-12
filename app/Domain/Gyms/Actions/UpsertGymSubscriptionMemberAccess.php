<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRepository;
use Domain\Gyms\Enums\GymSubscriptionMemberAccessPeriodType;
use Domain\Gyms\Models\GymSubscriptionMemberAccess;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertGymSubscriptionMemberAccess
{
    private GymSubscriptionMemberAccessRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param GymSubscriptionMemberAccessRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        GymSubscriptionMemberAccessRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccess
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionMemberAccess
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
            'member_id' => 'required|numeric',
            'date_from' => 'required',
            'date_to' => 'required',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:gym_subscription_member_access';
        }

        return $rules;
    }
}
