<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionQuotasRepository;
use Domain\Gyms\Enums\GymSubscriptionQuotaState;
use Domain\Gyms\Models\GymSubscriptionQuota;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertGymSubscriptionQuota
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionQuota
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
            'amount' => 'required|numeric',
            'date' => 'required',
            'gym_subscription_id' => 'required',
            'state' => 'required|in:' . implode(',', collect(GymSubscriptionQuotaState::cases())->pluck('value')->toArray()),
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:gym_subscription_quotas';
        }

        return $rules;
    }
}
