<?php

namespace Domain\Companies\Repositories;

use Domain\Companies\Contracts\Repositories\CompaniesRepository as RepositoryInterface;
use Domain\Companies\Models\Company;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class CompaniesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Company
     */
    private Company $entity;

    /**
     * @param Company $entity
     */
    public function __construct(Company $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return Company
     */
    public function getEntity(): Company
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
