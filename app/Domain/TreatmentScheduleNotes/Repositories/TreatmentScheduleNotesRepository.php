<?php

namespace Domain\TreatmentScheduleNotes\Repositories;

use Domain\TreatmentScheduleNotes\Contracts\Repositories\TreatmentScheduleNotesRepository as RepositoryInterface;
use Domain\TreatmentScheduleNotes\Models\TreatmentScheduleNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class TreatmentScheduleNotesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var TreatmentScheduleNote
     */
    private TreatmentScheduleNote $entity;

    /**
     * @param TreatmentScheduleNote $entity
     */
    public function __construct(TreatmentScheduleNote $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return TreatmentScheduleNote
     */
    public function getEntity(): TreatmentScheduleNote
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

        if (array_key_exists('note', $filters) && !empty($filters['note'])) {
            $query->where('note', 'like', '%'. $filters['note'] . '%');
        }

        if (array_key_exists('date_from', $filters) && !empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (array_key_exists('date_to', $filters) && !empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        if (array_key_exists('employee_id', $filters) && !empty($filters['employee_id'])) {
            $query->where('employee_id', '=', $filters['employee_id']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

}
