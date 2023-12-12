<?php

namespace Domain\Gyms\Repositories;

use Domain\Gyms\Contracts\Repositories\GymFeeTypesRepository as RepositoryInterface;
use Domain\Gyms\Models\GymFeeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class GymFeeTypesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var GymFeeType
     */
    private GymFeeType $entity;

    /**
     * @param GymFeeType $entity
     */
    public function __construct(GymFeeType $entity)
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
        $query = $this->getEntity()->newQuery()->select('gym_fee_types.*');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }

        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }

        if (array_key_exists('period_type', $filters) && !empty($filters['period_type'])) {
            $query->where('period_type', '=', $filters['period_type']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return GymFeeType
     */
    public function getEntity(): GymFeeType
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
