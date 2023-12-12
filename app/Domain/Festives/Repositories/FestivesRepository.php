<?php

namespace Domain\Festives\Repositories;

use Domain\Festives\Contracts\Repositories\FestivesRepository as RepositoryInterface;
use Domain\Festives\Models\Festive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class FestivesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Festive
     */
    private Festive $entity;

    /**
     * @param Festive $entity
     */
    public function __construct(Festive $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return Festive
     */
    public function getEntity(): Festive
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

        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }

        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }

        if (array_key_exists('description', $filters) && !empty($filters['description'])) {
            $query->where('description', 'like', '%'. $filters['description'] . '%');
        }

        if (array_key_exists('type', $filters) && !empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (array_key_exists('date', $filters) && !empty($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (array_key_exists('date_from', $filters) && !empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (array_key_exists('date_to', $filters) && !empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

}
