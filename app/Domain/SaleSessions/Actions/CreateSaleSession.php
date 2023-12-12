<?php

namespace Domain\SaleSessions\Actions;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\Enums\SessionStatus;
use Domain\SaleSessions\Enums\SessionType;
use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreateSaleSession
{
    private SaleSessionsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param SaleSessionsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        SaleSessionsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return SaleSession
     * @throws ValidationException
     * @throws \Throwable
     */
    public function __invoke(array $data): SaleSession
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        throw_if($this->repository->activeSession(), 'RuntimeException', 'Session already opened');

        return $this->repository->add([
            'session_status' => SessionStatus::OPEN->value,
            ...Arr::only(
                $data,
                [

                    'session_type',
                    'employee_id',
                    'start_date',
                    'start_amount'
                ]
            )
        ]);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'session_type' => 'required|in:' . implode(',', collect(SessionType::cases())->pluck('value')->toArray()),
            'employee_id' => 'required|numeric',
            'start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'start_amount' => 'required|numeric',
        ];
    }
}
