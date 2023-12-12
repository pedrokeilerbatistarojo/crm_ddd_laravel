<?php

namespace Domain\Products\Repositories;

use Domain\Products\Contracts\Repositories\ProductDiscountsRepository as RepositoryInterface;
use Domain\Products\Models\ProductDiscount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ProductDiscountsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var ProductDiscount
     */
    private ProductDiscount $entity;

    /**
     * @param ProductDiscount $entity
     */
    public function __construct(ProductDiscount $entity)
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
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }

        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }

        if (array_key_exists('product_id', $filters)) {
            $query->where('product_id', '=', $filters['product_id']);
        }

        if (array_key_exists('discount_id', $filters)) {
            $query->where('discount_id', '=', $filters['discount_id']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return ProductDiscount
     */
    public function getEntity(): ProductDiscount
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
