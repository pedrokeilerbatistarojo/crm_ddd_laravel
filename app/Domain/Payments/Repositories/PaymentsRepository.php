<?php

namespace Domain\Payments\Repositories;

use Domain\Payments\Contracts\Repositories\PaymentsRepository as RepositoryInterface;
use Domain\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class PaymentsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Payment
     */
    private Payment $entity;

    /**
     * @param Payment $entity
     */
    public function __construct(Payment $entity)
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
        $query = $this->getEntity()->newQuery()->select('payments.*');

        if (array_key_exists('client_id', $filters) && !empty($filters['client_id'])) {
            $query->join('orders', 'orders.id', '=', 'payments.order_id');
            $query->whereIn('orders.client_id', (array)$filters['client_id']);
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

        if (array_key_exists('order_id', $filters) && !empty($filters['order_id'])) {
            $query->whereIn('order_id', (array)$filters['order_id']);
        }

        if (array_key_exists('due_date', $filters) && !empty($filters['due_date'])) {
            $query->where('due_date', '=', $filters['due_date']);
        }

        if (array_key_exists('due_date_from', $filters) && !empty($filters['due_date_from'])) {
            $query->where('due_date', '>=', $filters['due_date_from']);
        }

        if (array_key_exists('due_date_to', $filters) && !empty($filters['due_date_to'])) {
            $query->where('due_date', '<=', $filters['due_date_to']);
        }

        if (array_key_exists('paid_date', $filters) && !empty($filters['paid_date'])) {
            $query->where('paid_date', '=', $filters['paid_date']);
        }

        if (array_key_exists('paid_date_from', $filters) && !empty($filters['paid_date_from'])) {
            $query->where('paid_date', '>=', $filters['paid_date_from']);
        }

        if (array_key_exists('paid_date_to', $filters) && !empty($filters['paid_date_to'])) {
            $query->where('paid_date', '<=', $filters['paid_date_to']);
        }

        if (array_key_exists('type', $filters) && !empty($filters['type'])) {
            $query->where('type', '=', $filters['type']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return Payment
     */
    public function getEntity(): Payment
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
