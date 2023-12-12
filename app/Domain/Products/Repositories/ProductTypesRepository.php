<?php

namespace Domain\Products\Repositories;

use Domain\Products\Contracts\Repositories\ProductTypesRepository as RepositoryInterface;
use Domain\Products\Models\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ProductTypesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var ProductType
     */
    private ProductType $entity;

    /**
     * @param ProductType $entity
     */
    public function __construct(ProductType $entity)
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

        if (array_key_exists('name', $filters) && !empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (array_key_exists('category_id', $filters) && !empty($filters['category_id'])) {
            $query->where('category_id', 'like', '%' . $filters['category_id'] . '%');
        }

        if (array_key_exists('priority', $filters)) {
            $query->where('priority', '=', $filters['priority']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return ProductType
     */
    public function getEntity(): ProductType
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
