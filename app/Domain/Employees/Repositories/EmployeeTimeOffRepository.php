<?php

namespace Domain\Employees\Repositories;

use Domain\Employees\Contracts\Repositories\EmployeeTimeOffRepository as RepositoryInterface;
use Domain\Employees\Models\EmployeeTimeOff;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class EmployeeTimeOffRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var EmployeeTimeOff
     */
    private EmployeeTimeOff $entity;

    /**
     * @param EmployeeTimeOff $entity
     */
    public function __construct(EmployeeTimeOff $entity)
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

        if (array_key_exists('employee_id', $filters) && $filters['employee_id']) {
            $query->where('employee_id', '=', $filters['employee_id']);
        }

        if (array_key_exists('type', $filters) && !empty($filters['type'])) {
            $query->where('type', '=', $filters['type']);
        }

        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }

        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }

        if (array_key_exists('date_from', $filters) && !empty($filters['date_from'])) {
            $query->where('from_date', '<=', $filters['date_from']);
        }

        if (array_key_exists('date_to', $filters) && !empty($filters['date_to'])) {
            $query->where('to_date', '>=', $filters['date_to']);
        }

        if (array_key_exists('from_date_from', $filters) && !empty($filters['from_date_from'])) {
            $query->where('from_date', '>=', $filters['from_date_from']);
        }

        if (array_key_exists('from_date_to', $filters) && !empty($filters['from_date_to'])) {
            $query->where('from_date', '<=', $filters['from_date_to']);
        }

        if (array_key_exists('to_date_from', $filters) && !empty($filters['to_date_from'])) {
            $query->where('to_date', '>=', $filters['to_date_from']);
        }

        if (array_key_exists('to_date_to', $filters) && !empty($filters['to_date_to'])) {
            $query->where('to_date', '<=', $filters['to_date_to']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return EmployeeTimeOff
     */
    public function getEntity(): EmployeeTimeOff
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
