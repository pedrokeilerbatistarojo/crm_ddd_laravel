<?php

namespace Domain\Orders\Repositories;

use Domain\Orders\Contracts\Repositories\OrderDetailsRepository as RepositoryInterface;
use Domain\Orders\Models\OrderDetail;
use Domain\Orders\Models\OrderDetailsCircuitReservations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class OrderDetailsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var OrderDetail
     */
    private OrderDetail $entity;

    /**
     * @param OrderDetail $entity
     */
    public function __construct(OrderDetail $entity)
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
        $query = $this->getEntity()->newQuery()->select('*');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $query->whereIn('order_details.id', (array)$filters['id']);
        }

        if (array_key_exists('order_id', $filters) && !empty($filters['order_id'])) {
            $query->whereIn('order_details.order_id', (array)$filters['order_id']);
        }

        if (array_key_exists('product_id', $filters) && !empty($filters['product_id'])) {
            $query->whereIn('order_details.product_id', (array)$filters['product_id']);
        }

        if (array_key_exists('created_at_from', $filters) && !empty($filters['created_at_from'])) {
            $query->where('order_details.created_at', '>=', $filters['created_at_from']);
        }

        if (array_key_exists('created_at_to', $filters) && !empty($filters['created_at_to'])) {
            $query->where('order_details.created_at', '<=', $filters['created_at_to']);
        }

        if (array_key_exists('company_id', $filters) && !empty($filters['company_id'])) {
            $query->join('orders', 'orders.id', '=', 'order_details.order_id');
            $query->whereIn('orders.company_id', (array)$filters['company_id']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return OrderDetail
     */
    public function getEntity(): OrderDetail
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
