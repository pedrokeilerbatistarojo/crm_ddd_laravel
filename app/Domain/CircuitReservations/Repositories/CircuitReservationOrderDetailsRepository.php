<?php

namespace Domain\CircuitReservations\Repositories;

use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationOrderDetailsRepository as RepositoryInterface;
use Domain\CircuitReservations\Models\CircuitReservationOrderDetail;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class CircuitReservationOrderDetailsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var CircuitReservationOrderDetail
     */
    private CircuitReservationOrderDetail $entity;

    /**
     * @param CircuitReservationOrderDetail $entity
     */
    public function __construct(CircuitReservationOrderDetail $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return CircuitReservationOrderDetail
     */
    public function getEntity(): CircuitReservationOrderDetail
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
