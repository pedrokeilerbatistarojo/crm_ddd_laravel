<?php

namespace Support\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Support\Models\Entity;

interface Repository
{
    /**
     * @param array $data
     *
     * @return Entity|null
     */
    public function add(array $data): ?Entity;

    /**
     * @param array $data
     *
     * @return bool
     */
    public function addMany(array $data): bool;

    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $data
     *
     * @return Entity|null
     */
    public function delete(array $data): ?Entity;

    /**
     * @param array $data
     */
    public function deleteIn(array $data): void;

    /**
     * @param array $where
     *
     * @return bool|null
     */
    public function deleteWhere(array $where): ?bool;

    /**
     * @param array $data
     *
     * @return Entity|null
     */
    public function edit(array $data): ?Entity;

    /**
     * @param array $where
     * @param array $data
     *
     * @return bool
     */
    public function editMany(array $where, array $data): bool;

    /**
     * @param array $ids
     * @param array $data
     *
     * @return bool
     */
    public function editIn(array $ids, array $data): bool;

    /**
     * @param array $search
     * @param array $data
     *
     * @return Entity|null
     */
    public function editOrCreate(array $search, array $data): ?Entity;

    /**
     * @param $id
     *
     * @return Entity|null
     */
    public function find($id): ?Entity;

    /**
     * @param array $ids
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function findIn(array $ids, ?array $orderBy = null, ?int $take = null): Collection;

    /**
     * @param $id
     *
     * @return Entity|null
     */
    public function findOrNew($id): ?Entity;

    /**
     * @param $where
     *
     * @return Entity|null
     */
    public function first($where): ?Entity;

    /**
     * @param $where
     *
     * @return Entity|null
     */
    public function firstOrCreate($where): ?Entity;

    /**
     * @param $where
     *
     * @return Entity|null
     */
    public function firstOrNew($where): ?Entity;

    /**
     * @param $query
     *
     * @return array|null
     */
    public function firstRaw($query): ?array;

    /**
     * @param array $values
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function getBetween(array $values, ?array $orderBy = null, ?int $take = null): Collection;

    /**
     * @param array $values
     * @param array|null $orderBy
     *
     * @return Entity|null
     */
    public function getFirstBetween(array $values, ?array $orderBy = null): ?Entity;

    /**
     * @param array $values
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function getByFields(array $values, ?array $orderBy = null, ?int $take = null): Collection;

    /**
     * @param array $values
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function getByFieldsIn(array $values, ?array $orderBy = null, ?int $take = null): Collection;

    /**
     * @param array $values
     * @param array|null $orderBy
     *
     * @return ?Entity
     */
    public function getFirstByFields(array $values, ?array $orderBy = null): ?Entity;

    /**
     * @return Entity
     */
    public function getEntity(): Entity;

    /**
     * @param            $whereRaw
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function getWhereRaw($whereRaw, ?array $orderBy = null, ?int $take = null): Collection;

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity): void;
}
