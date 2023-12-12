<?php

namespace Domain\TreatmentReservations\Repositories;

use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationsRepository as RepositoryInterface;
use Domain\TreatmentReservations\Models\TreatmentReservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class TreatmentReservationsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var TreatmentReservation
     */
    private TreatmentReservation $entity;

    /**
     * @param TreatmentReservation $entity
     */
    public function __construct(TreatmentReservation $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return TreatmentReservation
     */
    public function getEntity(): TreatmentReservation
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

    /**
     * @param int $id
     * @return Collection
     */
    public function findByOrderDetail(int $id): Collection
    {
        return $this->getEntity()
            ->query()
            ->select(['treatment_reservations.*'])
            ->join(
                'treatment_reservations_order_details',
                'treatment_reservations_order_details.id',
                '=',
                'treatment_reservations.id'
            )
            ->where('order_detail_id', $id)
            ->get();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Collection
     */
    public function relatedOrderDetails(int $id, array $columns = ['*']): Collection
    {
        return DB::table('treatment_reservations_order_details')
            ->select($columns)
            ->where('id', $id)
            ->get();
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
        $query = $this->getEntity()->newQuery()->select('treatment_reservations.*');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('employee_id', $filters) && !empty($filters['employee_id'])) {
            $query->whereIn('employee_id', (array)$filters['employee_id']);
        }

        if (array_key_exists('client_id', $filters) && !empty($filters['client_id'])) {
            $query->whereIn('client_id', (array)$filters['client_id']);
        }

        if (array_key_exists('client', $filters) && !empty($filters['client'])) {
            $query->join('clients', 'clients.id', '=', 'treatment_reservations.client_id');
            $query->where('clients.name', 'like', '%' . $filters['client'] . '%');
        }

        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('treatment_reservations.created_by', (array)$filters['created_by']);
        }

        if (array_key_exists('used', $filters)) {
            $query->where('used', '=', $filters['used']);
        }

        if (array_key_exists('date', $filters)) {
            $query->where('date', $filters['date']);
        }

        if (array_key_exists('date_from', $filters) && !empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (array_key_exists('date_to', $filters) && !empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        if (array_key_exists('deleted_at', $filters) && !empty($filters['deleted_at'])) {
            $query->onlyTrashed();
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    //endregion
}
