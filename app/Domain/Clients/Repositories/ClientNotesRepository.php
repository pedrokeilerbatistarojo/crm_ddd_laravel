<?php

namespace Domain\Clients\Repositories;

use Domain\Clients\Contracts\Repositories\ClientNotesRepository as RepositoryInterface;
use Domain\Clients\Models\ClientNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ClientNotesRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var ClientNote
     */
    private ClientNote $entity;

    /**
     * @param ClientNote $entity
     */
    public function __construct(ClientNote $entity)
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
     * @return ClientNote
     */
    public function getEntity(): ClientNote
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
