<?php

namespace Domain\SaleSessions\Contracts\Services;

use Domain\SaleSessions\DataTransferObjects\SaleSessionEntity;
use Domain\SaleSessions\DataTransferObjects\SaleSessionPDFResponse;
use Domain\SaleSessions\DataTransferObjects\SaleSessionSearchRequest;
use Domain\SaleSessions\DataTransferObjects\SaleSessionSearchResponse;

interface SaleSessionsService
{
    /**
     * @param array $includes
     * @return SaleSessionEntity|null
     */
    public function activeSession(array $includes = []): ?SaleSessionEntity;

    /**
     * @param array $data
     * @return SaleSessionEntity|null
     */
    public function close(array $data): ?SaleSessionEntity;

    /**
     * @param array $data
     * @return SaleSessionEntity|null
     */
    public function create(array $data): ?SaleSessionEntity;

    /**
     * @param array $data
     * @return SaleSessionEntity|null
     */
    public function delete(array $data): ?SaleSessionEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return SaleSessionEntity|null
     */
    public function find(int $id, array $includes = []): ?SaleSessionEntity;

    /**
     * @param int $id
     * @return SaleSessionPDFResponse
     */
    public function ordersPdf(int $id): SaleSessionPDFResponse;

    /**
     * @param int $id
     * @return SaleSessionPDFResponse
     */
    public function paymentsPdf(int $id): SaleSessionPDFResponse;

    /**
     * @param array $includes
     * @return SaleSessionEntity|null
     */
    public function reopen(array $includes = []): ?SaleSessionEntity;

    /**
     * @param SaleSessionSearchRequest $request
     * @return SaleSessionSearchResponse
     */
    public function search(SaleSessionSearchRequest $request): SaleSessionSearchResponse;

    /**
     * @param array $data
     * @return SaleSessionEntity|null
     */
    public function update(array $data): ?SaleSessionEntity;
}
