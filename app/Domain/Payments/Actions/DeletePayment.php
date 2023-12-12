<?php

namespace Domain\Payments\Actions;

use Domain\Payments\Contracts\Repositories\PaymentsRepository;
use Domain\Payments\Models\Payment;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class DeletePayment
{
    private PaymentsRepository $repository;
    private Factory $validatorFactory;

    /**
     * @param PaymentsRepository $repository
     * @param Factory $validatorFactory
     */
    public function __construct(
        PaymentsRepository $repository,
        Factory $validatorFactory
    ) {
        $this->repository = $repository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @return Payment
     * @throws ValidationException
     */
    public function __invoke(array $data): Payment
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
            'id' => 'required|exists:payments'
        ];
    }
}
