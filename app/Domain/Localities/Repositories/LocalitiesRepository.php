<?php

namespace Domain\Localities\Repositories;

use Domain\Localities\Contracts\Repositories\LocalitiesRepository as RepositoryInterface;
use Domain\Localities\Models\Locality;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class LocalitiesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Locality
     */
    private Locality $entity;

    /**
     * @param Locality $entity
     */
    public function __construct(Locality $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return Locality
     */
    public function getEntity(): Locality
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

        if (array_key_exists('zip_code', $filters) && !empty($filters['zip_code'])) {
            $query->where('zip_code', $filters['zip_code']);
        }

        if (array_key_exists('population', $filters) && !empty($filters['population'])) {
            $query->where('population', 'like', '%'. $filters['population'] . '%');
        }

        if (array_key_exists('province_id', $filters) && !empty($filters['province_id'])) {
            $query->where('province_id', (int)$filters['province_id']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }
}
