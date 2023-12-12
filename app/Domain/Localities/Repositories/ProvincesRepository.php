<?php

namespace Domain\Localities\Repositories;

use Domain\Localities\Contracts\Repositories\ProvincesRepository as RepositoryInterface;
use Domain\Localities\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ProvincesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Province
     */
    private Province $entity;

    /**
     * @param Province $entity
     */
    public function __construct(Province $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return Province
     */
    public function getEntity(): Province
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

        if (array_key_exists('name', $filters) && !empty($filters['name'])) {
            $query->where('name', 'like', '%'. $filters['name'] . '%');
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }
}
