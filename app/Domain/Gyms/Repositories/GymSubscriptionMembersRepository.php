<?php

namespace Domain\Gyms\Repositories;

use Domain\Gyms\Contracts\Repositories\GymSubscriptionMembersRepository as RepositoryInterface;
use Domain\Gyms\Models\GymSubscriptionMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class GymSubscriptionMembersRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var GymSubscriptionMember
     */
    private GymSubscriptionMember $entity;

    /**
     * @param GymSubscriptionMember $entity
     */
    public function __construct(GymSubscriptionMember $entity)
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
        $query = $this->getEntity()->newQuery()->select('gym_subscription_members.*');
        
        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $query->whereIn('id', (array)$filters['id']);
        }

        if (array_key_exists('gym_subscription_id', $filters) && !empty($filters['gym_subscription_id'])) {
            $query->whereIn('gym_subscription_id', (array)$filters['gym_subscription_id']);
        }
        
        if (array_key_exists('created_by', $filters) && !empty($filters['created_by'])) {
            $query->whereIn('created_by', (array)$filters['created_by']);
        }
        
        if (array_key_exists('last_modified_by', $filters) && !empty($filters['last_modified_by'])) {
            $query->whereIn('last_modified_by', (array)$filters['last_modified_by']);
        }

        $query->orderBy($sortField, $sortType->value);

        return $query;
    }

    /**
     * @return GymSubscriptionMember
     */
    public function getEntity(): GymSubscriptionMember
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
