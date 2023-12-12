<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionsRepository;
use Domain\Gyms\Models\GymSubscription;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteGymSubscription
{
    private GymSubscriptionsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param GymSubscriptionsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        GymSubscriptionsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return GymSubscription
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscription
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
            'id' => 'required|exists:gym_subscriptions'
        ];
    }
}
