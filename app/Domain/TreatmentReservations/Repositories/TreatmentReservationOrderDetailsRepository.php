<?php

namespace Domain\TreatmentReservations\Repositories;

use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationOrderDetailsRepository as RepositoryInterface;
use Domain\TreatmentReservations\Models\TreatmentReservationOrderDetail;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class TreatmentReservationOrderDetailsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var TreatmentReservationOrderDetail
     */
    private TreatmentReservationOrderDetail $entity;

    /**
     * @param TreatmentReservationOrderDetail $entity
     */
    public function __construct(TreatmentReservationOrderDetail $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return TreatmentReservationOrderDetail
     */
    public function getEntity(): TreatmentReservationOrderDetail
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
