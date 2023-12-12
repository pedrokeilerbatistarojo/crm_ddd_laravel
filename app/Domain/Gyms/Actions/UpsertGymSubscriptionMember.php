<?php

namespace Domain\Gyms\Actions;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMembersRepository;
use Domain\Gyms\Enums\GymSubscriptionMemberPeriodType;
use Domain\Gyms\Models\GymSubscriptionMember;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertGymSubscriptionMember
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): GymSubscriptionMember
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
            'gym_subscription_id' => 'required|numeric',
            'client_id' => 'required|numeric',
            'date_from' => 'required',
            'date_to' => 'required'
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:gym_subscription_members';
        }

        return $rules;
    }
}
