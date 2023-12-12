<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRightsRepository;
use Domain\Gyms\Models\GymSubscriptionMemberAccessRight;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteGymSubscriptionMemberAccessRight
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
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionMemberAccessRight
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
            'id' => 'required|exists:gym_subscription_member_access_rights'
        ];
    }
}
