<?php

namespace Domain\Orders\Repositories;

use Domain\Orders\Contracts\Repositories\OrdersApprovalRepository as RepositoryInterface;
use Domain\Orders\Models\OrderApproval;
use Domain\Orders\Transformers\OrderApprovalTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class OrdersApprovalRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var OrderApproval
     */
    private OrderApproval $entity;

    /**
     * @param OrderApproval $entity
     */
    public function __construct(OrderApproval $entity)
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

        if (array_key_exists('id', $filters)) {
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('locator', $filters) && !empty($filters['locator'])) {
            $query->where('locator', '=', $filters['locator']);
        }

        if (array_key_exists('is_duplicated', $filters) && !empty($filters['is_duplicated'])) {
            $query->where('is_duplicated', '=', $filters['is_duplicated']);
        }

        if (array_key_exists('is_reservation', $filters) && $filters['is_reservation'] !== null) {
            $query->where('is_reservation', '=', $filters['is_reservation']);
        }

        if (array_key_exists('created_at_to', $filters) && !empty($filters['created_at_to'])) {
            $query->where('created_at', '<=', $filters['created_at_to']);
        }

        if (array_key_exists('created_at_from', $filters) && !empty($filters['created_at_from'])) {
            $query->where('created_at', '>=', $filters['created_at_from']);
        }

        if (array_key_exists('client', $filters) && !empty($filters['client'])) {
            $arrApproveClientIds = $this->filterByParam($query, 'fullname', $filters['client']);
            $query->whereIn('id', $arrApproveClientIds);
        }

        if (array_key_exists('email', $filters) && !empty($filters['email'])) {
            $arrApproveClientIds = $this->filterByParam($query, 'email', $filters['email']);
            $query->whereIn('id', $arrApproveClientIds);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    private function filterByParam($query, string $param, string $filterName): array
    {
        $records = $query->paginate();
        $collection = new \League\Fractal\Resource\Collection($records->items(), app(OrderApprovalTransformer::class), 'data');
        $arrApprovesClient = [];

        foreach ($collection->getData() as $orderApproval) {
            $paramVal = $orderApproval->order_data['customer'][$param];
            if (stripos($paramVal, $filterName) !== false) {
                $arrApprovesClient[] = $orderApproval->id;
            }
        }

        return $arrApprovesClient;
    }

    /**
     * @return OrderApproval
     */
    public function getEntity(): OrderApproval
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
