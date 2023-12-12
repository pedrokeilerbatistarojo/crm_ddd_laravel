<?php

namespace Domain\SaleSessions\Actions;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\Enums\SessionType;
use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class UpdateSaleSession
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
     */
    public function __invoke(array $data): SaleSession
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        return $this->repository->edit(
            Arr::only(
                $data,
                [
                    'id',
                    'session_type',
                    'employee_id',
                    'start_date',
                    'end_date',
                    'start_amount',
                    'end_amount'
                ]
            )
        );
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'id' => 'required|exists:sale_sessions',
            'session_type' => 'required|in:' . implode(',', collect(SessionType::cases())->pluck('value')->toArray()),
            'employee_id' => 'required|numeric',
            'start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'end_date' => 'nullable|date|date_format:Y-m-d H:i:s|after:start_date',
            'start_amount' => 'required|numeric',
            'end_amount' => 'nullable|numeric',
        ];
    }
}
