<?php

namespace Domain\Payments\Actions;

use Domain\Payments\Contracts\Repositories\PaymentsRepository;
use Domain\Payments\Enums\PaymentType;
use Domain\Payments\Models\Payment;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpsertPayment
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function __invoke(array $data): Payment
    {
        $validator = $this->validatorFactory->make($data, $this->rules($data));

        $validator->validate();

        $method = array_key_exists('id', $data) ? 'edit' : 'add';

        $data['amount'] = $data['paid_amount'] - $data['returned_amount'];

        return $this->repository->$method($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function rules($data): array
    {
        $rules = [
            'order_id' => 'required|numeric',
            'due_date' => 'required|date|date_format:Y-m-d H:i:s',
            'paid_date' => 'required|date|date_format:Y-m-d H:i:s',
            'type' => 'required|in:' . implode(',', collect(PaymentType::cases())->pluck('value')->toArray()),
            'paid_amount' => 'required|numeric',
            'returned_amount' => 'required|numeric',
        ];

        if (array_key_exists('id', $data)) {
            $rules['id'] = 'required|exists:payments';
        }

        return $rules;
    }
}
