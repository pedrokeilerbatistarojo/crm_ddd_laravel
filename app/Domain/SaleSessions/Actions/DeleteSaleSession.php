<?php

namespace Domain\SaleSessions\Actions;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeleteSaleSession
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

        return $this->repository->delete($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        return [
            'id' => 'required|exists:sale_sessions'
        ];
    }
}
