<?php

namespace Domain\Employees\Repositories;

use Domain\Employees\Contracts\Repositories\EmployeesRepository as RepositoryInterface;
use Domain\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class EmployeesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Employee
     */
    private Employee $entity;

    /**
     * @param Employee $entity
     */
    public function __construct(Employee $entity)
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

        if (array_key_exists('active', $filters)) {
            $query->where('active', '=', (bool) $filters['active']);
        }

        if (array_key_exists('is_specialist', $filters)) {
            $query->where('is_specialist', '=', (bool) $filters['is_specialist']);
        }

        if (array_key_exists('name', $filters) && !empty($filters['name'])) {
            $query->whereRaw(
                'CONCAT_WS(" ", first_name, last_name, second_last_name) like "%' . $filters['name'] . '%"'
            );
        }

        if (array_key_exists('first_name', $filters) && !empty($filters['first_name'])) {
            $query->where('first_name', '=', $filters['first_name']);
        }

        if (array_key_exists('last_name', $filters) && !empty($filters['last_name'])) {
            $query->where('last_name', '=', $filters['last_name']);
        }

        if (array_key_exists('second_last_name', $filters) && !empty($filters['second_last_name'])) {
            $query->where('second_last_name', '=', $filters['second_last_name']);
        }

        if (array_key_exists('email', $filters) && !empty($filters['email'])) {
            $query->where('email', '=', $filters['email']);
        }

        if (array_key_exists('phone', $filters) && !empty($filters['phone'])) {
            $query->where('phone', '=', $filters['phone']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return Employee
     */
    public function getEntity(): Employee
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
