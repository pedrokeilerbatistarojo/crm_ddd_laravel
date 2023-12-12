<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMembersRepository;
use Domain\Gyms\Models\GymSubscriptionMember;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteGymSubscriptionMember
{
    private GymSubscriptionMembersRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param GymSubscriptionMembersRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        GymSubscriptionMembersRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return GymSubscriptionMember
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionMember
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
            'id' => 'required|exists:gym_subscription_members'
        ];
    }
}
