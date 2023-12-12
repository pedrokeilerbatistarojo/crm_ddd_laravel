<?php

namespace Domain\SaleSessions\Repositories;

use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository as RepositoryInterface;
use Domain\SaleSessions\Enums\SessionStatus;
use Domain\SaleSessions\Models\SaleSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class SaleSessionsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var SaleSession
     */
    private SaleSession $entity;

    /**
     * @param SaleSession $entity
     */
    public function __construct(SaleSession $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return SaleSession|null
     */
    public function activeSession(): ?SaleSession
    {
        return $this->getEntity()->query()->whereIn('session_status', [SessionStatus::OPEN->value, SessionStatus::REOPENED->value])->first();
    }

    /**
     * @return SaleSession|null
     */
    public function lastSession(): ?SaleSession
    {
        return $this->getEntity()->query()->orderBy('id', 'DESC')->first();
    }

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

        if (array_key_exists('employee_id', $filters) && !empty($filters['employee_id'])) {
            $query->whereIn('employee_id', (array)$filters['employee_id']);
        }

        if (array_key_exists('session_type', $filters) && !empty($filters['session_type'])) {
            $query->where('session_type', '=', $filters['session_type']);
        }

        if (array_key_exists('start_date', $filters) && !empty($filters['start_date'])) {
            $query->where('start_date', '=', $filters['start_date']);
        }

        if (array_key_exists('start_date_from', $filters) && !empty($filters['start_date_from'])) {
            $query->where('start_date', '>=', $filters['start_date_from']);
        }

        if (array_key_exists('start_date_to', $filters) && !empty($filters['start_date_to'])) {
            $query->where('start_date', '<=', $filters['start_date_to']);
        }

        if (array_key_exists('end_date', $filters) && !empty($filters['end_date'])) {
            $query->where('end_date', '=', $filters['end_date']);
        }

        if (array_key_exists('end_date_from', $filters) && !empty($filters['end_date_from'])) {
            $query->where('end_date', '>=', $filters['end_date_from']);
        }

        if (array_key_exists('end_date_to', $filters) && !empty($filters['end_date_to'])) {
            $query->where('end_date', '<=', $filters['end_date_to']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return SaleSession
     */
    public function getEntity(): SaleSession
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
