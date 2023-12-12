<?php

namespace Domain\Users\Repositories;

use Domain\Users\Contracts\Repositories\UsersRepository as RepositoryInterface;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class UsersRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var User
     */
    private User $entity;

    /**
     * @param User $entity
     */
    public function __construct(User $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return User
     */
    public function getEntity(): User
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

        if (array_key_exists('name', $filters) && !empty($filters['name'])) {
            $query->whereRaw(
                'name like "%' . $filters['name'] . '%"'
            );
        }

        if (array_key_exists('username', $filters) && !empty($filters['username'])) {
            $query->where('username', '=', $filters['username']);
        }

        if (array_key_exists('email', $filters) && !empty($filters['email'])) {
            $query->where('email', '=', $filters['email']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }


    //endregion
}
