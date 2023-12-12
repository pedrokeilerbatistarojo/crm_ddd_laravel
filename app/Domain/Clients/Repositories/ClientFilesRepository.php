<?php

namespace Domain\Clients\Repositories;

use Domain\Clients\Contracts\Repositories\ClientFilesRepository as RepositoryInterface;
use Domain\Clients\Models\ClientFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ClientFilesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var ClientFile
     */
    private ClientFile $entity;

    /**
     * @param ClientFile $entity
     */
    public function __construct(ClientFile $entity)
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

        if (array_key_exists('id', $filters)) {
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('client_id', $filters)) {
            $query->whereIn('client_id', (array)$filters['client_id']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return ClientFile
     */
    public function getEntity(): ClientFile
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
