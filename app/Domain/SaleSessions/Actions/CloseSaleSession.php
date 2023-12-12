<?php

namespace Domain\SaleSessions\Actions;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\Enums\SessionStatus;
use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CloseSaleSession
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

        return $this->repository->edit([
            'session_status' => SessionStatus::CLOSED->value,
            'end_date' => !empty($data['end_date']) ? $data['end_date'] : Carbon::now()->toDateTimeString(),
            ...Arr::only($data, ['id', 'end_amount', 'closed_by'])
        ]);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'id' => 'required|exists:sale_sessions',
            'end_date' => 'nullable|date|date_format:Y-m-d H:i:s',
            'end_amount' => 'required|numeric',
            'closed_by' => 'required|numeric',
        ];
    }
}
