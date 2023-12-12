<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRepository;
use Domain\Gyms\Models\GymSubscriptionMemberAccess;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteGymSubscriptionMemberAccess
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
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionMemberAccess
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
            'id' => 'required|exists:gym_subscription_member_access'
        ];
    }
}
