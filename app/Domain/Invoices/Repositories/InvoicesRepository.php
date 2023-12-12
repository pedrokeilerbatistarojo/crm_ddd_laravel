<?php

namespace Domain\Invoices\Repositories;

use Domain\Invoices\Contracts\Repositories\InvoicesRepository as RepositoryInterface;
use Domain\Invoices\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class InvoicesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Invoice
     */
    private Invoice $entity;

    /**
     * @param Invoice $entity
     */
    public function __construct(Invoice $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @param array $filters
     * @param string $sortField
     * @param SQLSort $sortType
     * @return Collection
     */
    public function search(array $filters, string $sortField, SQLSort $sortType): Collection
    {
        return $this->searchQueryBuilder($filters, $sortField, $sortType)->get();
    }

    /**
     * @param array $filters
     * @param string $sortField
     * @param SQLSort $sortType
     * @return Builder
     */
    public function searchQueryBuilder(array $filters, string $sortField, SQLSort $sortType): Builder
    {
        $query = $this->getEntity()->newQuery()->select('invoices.*');

        if (array_key_exists('client_id', $filters) && !empty($filters['client_id'])) {
            $query->join('clients', 'clients.id', '=', 'invoices.client_id');
            $query->whereIn('clients.id', (array)$filters['client_id']);
        }
        
        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $query->whereIn('id', (array)$filters['id']);
        }
        
        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }
        
        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }
        
        if (array_key_exists('invoice_type', $filters) && !empty($filters['invoice_type'])) {
            $query->where('invoice_type', '=', $filters['invoice_type']);
        }

        if (array_key_exists('invoice_date', $filters) && !empty($filters['invoice_date'])) {
            $query->where('invoice_date', '=', $filters['invoice_date']);
        }

        if (array_key_exists('invoice_date_from', $filters) && !empty($filters['invoice_date_from'])) {
            $query->where('invoice_date', '>=', $filters['invoice_date_from']);
        }

        if (array_key_exists('invoice_date_to', $filters) && !empty($filters['invoice_date_to'])) {
            $query->where('invoice_date', '<=', $filters['invoice_date_to']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return Invoice
     */
    public function getEntity(): Invoice
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     *
     * @return void
     */
    public function setEntity(Entity $entity): void
    {
        $this->entity = $entity;
    }

    //endregion
}
