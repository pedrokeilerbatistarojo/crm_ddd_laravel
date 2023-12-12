<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionsRepository;
use Domain\Gyms\Enums\GymSubscriptionPaymentType;
use Domain\Gyms\Models\GymSubscription;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertGymSubscription
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscription
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
            'client_id' => 'required|numeric',
            'gym_fee_type_id' => 'required|numeric',
            'gym_fee_type_name' => 'required',
            'price' => 'required|numeric',
            'activation_date' => 'required',
            'start_date' => 'required',
            'end_date' => 'nullable',
            'expiration_date' => 'required',
            'payment_day' => 'required|numeric',
            'payment_type' => 'required|in:' . implode(',', collect(GymSubscriptionPaymentType::cases())->pluck('value')->toArray())        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:gym_subscriptions';
        }

        return $rules;
    }
}
