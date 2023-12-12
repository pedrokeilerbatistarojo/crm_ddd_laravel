<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRightsRepository;
use Domain\Gyms\Enums\GymSubscriptionMemberAccessRightPeriodType;
use Domain\Gyms\Models\GymSubscriptionMemberAccessRight;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertGymSubscriptionMemberAccessRight
{
    private GymSubscriptionMemberAccessRightsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param GymSubscriptionMemberAccessRightsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        GymSubscriptionMemberAccessRightsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessRight
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionMemberAccessRight
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
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:gym_subscription_member_access_rights';
        }

        return $rules;
    }
}
