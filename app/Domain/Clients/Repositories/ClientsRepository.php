<?php

namespace Domain\Clients\Repositories;

use Domain\Clients\Contracts\Repositories\ClientsRepository as RepositoryInterface;
use Domain\Clients\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Support\Core\Enums\SQLSort;
use Support\Models\Entity;
use Support\Repositories\Implementations\Repository;

class ClientsRepository extends Repository implements RepositoryInterface
{
    //region Constructor And Fields

    /**
     * @var Client
     */
    private Client $entity;

    /**
     * @param Client $entity
     */
    public function __construct(Client $entity)
    {
        $this->entity = $entity;
    }

    //endregion

    //region RepositoryInterface">

    /**
     * @return Client
     */
    public function getEntity(): Client
    {
        return $this->entity;
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
        $query = $this->getEntity()->newQuery()
            ->select('*')
            ->whereNotIn('id', [config('system.telephone_sale_client_id'), config('system.counter_sale_client_id')])
        ;

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
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (array_key_exists('email', $filters) && !empty($filters['email'])) {
            $query->where('email', 'like', '%'.$filters['email'].'%');
        }

        if (array_key_exists('phone', $filters) && !empty($filters['phone'])) {
            $query->where('phone', '=', $filters['phone']);
        }

        if (array_key_exists('document', $filters) && !empty($filters['document'])) {
            $query->where('document', '=', $filters['document']);
        }

        if (array_key_exists('postcode', $filters) && !empty($filters['postcode'])) {
            $query->where('postcode', '=', $filters['postcode']);
        }

        if (array_key_exists('opt_in', $filters)) {
            $query->where('opt_in', '=', $filters['opt_in']);
        }

        if (array_key_exists('created_at_from', $filters) && !empty($filters['created_at_from'])) {
            $query->where('created_at', '>=', $filters['created_at_from']);
        }

        if (array_key_exists('created_at_to', $filters) && !empty($filters['created_at_to'])) {
            $query->where('created_at', '<=', $filters['created_at_to']);
        }

        if (array_key_exists('updated_at_from', $filters) && !empty($filters['updated_at_from'])) {
            $query->where('updated_at', '>=', $filters['updated_at_from']);
        }

        if (array_key_exists('updated_at_to', $filters) && !empty($filters['updated_at_to'])) {
            $query->where('updated_at', '<=', $filters['updated_at_to']);
        }

        if (array_key_exists('duplicate_by', $filters)) {
            if ($filters['duplicate_by'] == 'phone') {
                $query->whereRaw('exists (select 1 from clients as c where c.id <> clients.id and c.phone = clients.phone)');
            }
            if ($filters['duplicate_by'] == 'email') {
                $query->whereRaw('exists (select 1 from clients as c where c.id <> clients.id and c.email = clients.email)');
            }
            if ($filters['duplicate_by'] == 'document') {
                $query->whereRaw('exists (select 1 from clients as c where c.id <> clients.id and c.document = clients.document)');
            }
            if ($filters['duplicate_by'] == 'name') {
                $query->whereRaw("exists (select 1 from clients as c where c.id <> clients.id and c.name like clients.name)");
            }
        }

        $query->orderBy($sortField, $sortType->value);
        return $query;
    }

    /**
     * @param array $filters
     * @param string $sortField
     * @param SQLSort $sortType
     * @return Collection
     */
    public function duplicates(array $filters, string $sortField, SQLSort $sortType): Collection
    {
        return $this->duplicatesQueryBuilder($filters, $sortField, $sortType)->get();
    }

    /**
     * @param array $filters
     * @param string $sortField
     * @param SQLSort $sortType
     * @return Builder
     */
    public function duplicatesQueryBuilder(array $filters, string $sortField, SQLSort $sortType): Builder
    {
        $query = $this->getEntity()->newQuery()->select('*');

        if (array_key_exists('name', $filters)) {
            $query_str = "name like '%" . $filters['name'] . "%'";

            if (array_key_exists('document', $filters)) {
                $query_str .= " OR document = '" . $filters['document'] . "'";
            }
            if (array_key_exists('phone', $filters)) {
                $query_str .= " OR phone = '" . $filters['phone'] . "'";
            }
            if (array_key_exists('email', $filters)) {
                $query_str .= " OR email = '" . $filters['email'] . "'";
            }

            $query->whereRaw($query_str);
        } else {
            $query->where('id', '<', '0');
        }


        $query->orderBy($sortField, $sortType->value);

        return $query;
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
