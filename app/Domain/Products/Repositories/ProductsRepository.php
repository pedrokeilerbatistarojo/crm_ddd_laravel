<?php

namespace Domain\Products\Repositories;

use Domain\Products\Contracts\Repositories\ProductsRepository as RepositoryInterface;
use Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ProductsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Product
     */
    private Product $entity;

    /**
     * @param Product $entity
     */
    public function __construct(Product $entity)
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
        $query = $this->getEntity()->newQuery()->select('products.*');

        if (array_key_exists('name', $filters) && !empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (array_key_exists('description', $filters) && !empty($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }

        if (array_key_exists('product_type_id', $filters)) {
            $query->where('product_type_id', '=', $filters['product_type_id']);
        }

        if (array_key_exists('product_type', $filters) && !empty($filters['product_type'])) {
            $query->join('product_types', 'product_types.id', '=', 'products.product_type_id');
            $query->where('product_types.name', 'like', '%' . $filters['product_type'] . '%');
        }

        if (array_key_exists('available', $filters)) {
            $query->where('available', '=', (bool) $filters['available']);
        }

        if (array_key_exists('online_sale', $filters)) {
            $query->where('online_sale', '=', (bool) $filters['online_sale']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return Product
     */
    public function getEntity(): Product
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
