<?php

namespace Domain\Invoices\Contracts\Services;

use Domain\Invoices\DataTransferObjects\InvoiceEntity;
use Domain\Invoices\DataTransferObjects\InvoiceSearchRequest;
use Domain\Invoices\DataTransferObjects\InvoiceSearchResponse;

interface InvoicesService
{
    /**
     * @param array $data
     * @return InvoiceEntity|null
     */
    public function create(array $data): ?InvoiceEntity;

    /**
     * @param int $id
     * @param array $includes
     * @return InvoiceEntity|null
     */
    public function find(int $id, array $includes = []): ?InvoiceEntity;

    /**
     * @param InvoiceSearchRequest $request
     * @return InvoiceSearchResponse
     */
    public function search(InvoiceSearchRequest $request): InvoiceSearchResponse;

    /**
     * @param array $data
     * @return InvoiceEntity|null
     */
    public function update(array $data): ?InvoiceEntity;
}
