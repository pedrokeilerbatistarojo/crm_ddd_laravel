<?php

namespace Domain\Payments\Contracts\Services;

use Domain\Payments\DataTransferObjects\PaymentEntity;
use Domain\Payments\DataTransferObjects\PaymentSearchRequest;
use Domain\Payments\DataTransferObjects\PaymentSearchResponse;

interface PaymentsService
{
    /**
     * @param array $data
     * @return PaymentEntity|null
     */
    public function create(array $data): ?PaymentEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return PaymentEntity|null
     */
    public function find(int $id, array $includes = []): ?PaymentEntity;

    /**
     * @param PaymentSearchRequest $request
     * @return PaymentSearchResponse
     */
    public function search(PaymentSearchRequest $request): PaymentSearchResponse;

    /**
     * @param array $data
     * @return PaymentEntity|null
     */
    public function update(array $data): ?PaymentEntity;
}
