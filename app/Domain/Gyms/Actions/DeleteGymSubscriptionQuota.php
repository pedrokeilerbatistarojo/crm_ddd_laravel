<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionQuotasRepository;
use Domain\Gyms\Models\GymSubscriptionQuota;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteGymSubscriptionQuota
{
    private GymSubscriptionQuotasRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param GymSubscriptionQuotasRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        GymSubscriptionQuotasRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return GymSubscriptionQuota
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionQuota
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
            'id' => 'required|exists:gym_subscription_quotas'
        ];
    }
}
