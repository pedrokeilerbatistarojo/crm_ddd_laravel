<?php

namespace Support\Repositories\Implementations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;
use Support\Exceptions\DatabaseException;
use Support\Models\Entity;
use Support\Repositories\Contracts\Repository as RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /**
     * @param array $data
     *
     * @return Entity|null
     */
    public function add(array $data): ?Entity
    {
        return $this->getEntity()->query()->create($data);
    }

    /**
     * @return Entity
     */
    abstract public function getEntity(): Entity;

    /**
     * @param array $data
     *
     * @return bool
     */
    public function addMany(array $data): bool
    {
        $entities = [];
        foreach ($data as $entityData) {
            if (count($this->getEntity()->getFillable())) {
                $entities[] = array_intersect_key($entityData, array_flip($this->getEntity()->getFillable()));
            } else {
                $entities[] = $entityData;
            }
        }

        $result = $this->getEntity()->insert($entities);

        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getEntity()->all();
    }

    /**
     * @param array $data
     *
     * @return Entity|null
     * @throws DatabaseException
     */
    public function delete(array $data): ?Entity
    {
        $primaryKey = $this->getEntity()->getKeyName();

        $deleteEntity = $this->find($data[$primaryKey]);

        if (!$deleteEntity) {
            return null;
        }

        if (!$deleteEntity->delete()) {
            throw new DatabaseException('Sorry, we could not delete the the record from the database.');
        }

        return $deleteEntity;
    }

    /**
     * @param $id
     *
     * @return Entity|null
     */
    public function find($id): ?Entity
    {
        return $this->getEntity()->query()->find($id);
    }

    /**
     * @param array $data
     */
    public function deleteIn(array $data): void
    {
        $primaryKey = $this->getEntity()->getKeyName();

        $this->getEntity()->query()->whereIn($primaryKey, $data[Str::plural($primaryKey)])->delete();
    }

    /**
     * @param array $where
     *
     * @return bool|null
     */
    public function deleteWhere(array $where): ?bool
    {
        $entity = $this->getEntity();

        foreach ($where as $field => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }

            foreach ($value as $val) {
                $entity = $entity->where($field, $val);
            }
        }

        return $entity->delete();
    }

    /**
     * @param array $data
     *
     * @return Entity|null
     * @throws DatabaseException
     */
    public function edit(array $data): ?Entity
    {
        $primaryKey = $this->getEntity()->getKeyName();

        $editedEntity = $this->find($data[$primaryKey]);

        if (!$editedEntity) {
            return null;
        }

        $this->updateEntityData($editedEntity, $data);

        if (!$editedEntity->save()) {
            throw new DatabaseException('Sorry, we could not save the data into the database.');
        }

        return $editedEntity;
    }

    /**
     * Applies the new data to the given entity. This is the point where one would put any custom transformation to be
     * further applied to the edited entity.
     *
     * @param Entity $editedEntity : The entity being changed.
     * @param array $data : The data coming from the caller to be put in the entity.
     */
    protected function updateEntityData(Entity $editedEntity, array $data): void
    {
        $editedEntity->fill($data);
    }

    /**
     * @param array $where
     * @param array $data
     *
     * @return bool
     */
    public function editMany(array $where, array $data): bool
    {
        $entity = $this->getEntity();

        foreach ($where as $field => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }

            foreach ($value as $val) {
                if (is_array($val)) {
                    $entity = $entity->where($field, $val[0], $val[1]);
                } else {
                    $entity = $entity->where($field, $val);
                }
            }
        }

        return $entity->update($data);
    }

    /**
     * @param array $ids
     * @param array $data
     *
     * @return bool
     */
    public function editIn(array $ids, array $data): bool
    {
        return $this->getEntity()->whereIn('id', $ids)->update($data);
    }

    /**
     * @param array $search
     * @param array $data
     *
     * @return Entity|null
     * @throws DatabaseException
     */
    public function editOrCreate(array $search, array $data): ?Entity
    {
        $where = [];

        foreach ($search as $column) {
            $where[$column] = $data[$column];
        }

        $entity = $this->getFirstByFields($where);

        $entity->fill($data);

        if (!$entity->save()) {
            throw new DatabaseException('Sorry, we could not save the data into the database.');
        }

        return $entity;
    }

    /**
     * @param array $values
     * @param array|null $orderBy
     *
     * @return Entity|null
     */
    public function getFirstByFields(array $values, ?array $orderBy = null): ?Entity
    {
        return $this->getByFields($values, $orderBy, 1)->first();
    }

    /**
     * @param array $values
     * @param array|null $orderBy
     * @param int|null $take
     *
     * $orderByExample = [ ["id", "name"], ["desc", "asc"] ]
     *
     * @return Collection
     */
    public function getByFields(array $values, ?array $orderBy = null, ?int $take = null): Collection
    {
        $entity = $this->getEntity();

        foreach ($values as $field => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }

            foreach ($value as $val) {
                if (is_array($val)) {
                    $entity = $entity->where($field, $val[0], $val[1]);
                } else {
                    $entity = $entity->where($field, $val);
                }
            }
        }

        if ($orderBy) {
            $this->addOrderByToEntity($entity, $orderBy);
        }

        if ($take) {
            $entity = $entity->take($take);
        }

        return $entity->get();
    }

    /**
     * @param $entity
     * @param $value
     */
    private function addOrderByToEntity($entity, $value): void
    {
        if (!is_array($value[0])) {
            $value[0] = [$value[0]];
        }

        if (!is_array($value[1])) {
            $value[1] = [$value[1]];
        }

        foreach ($value[0] as $index => $field) {
            $entity->orderBy($field, $value[1][$index]);
        }
    }

    /**
     * @param array $ids
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function findIn(array $ids, ?array $orderBy = null, ?int $take = null): Collection
    {
        $entity = $this->getEntity();

        $entity = $entity->query()->whereIn($entity->getKeyName(), $ids);

        if ($orderBy) {
            $entity = $entity->orderBy($orderBy[0], $orderBy[1]);
        }

        if ($take) {
            $entity = $entity->take($take);
        }

        return $entity->get();
    }

    /**
     * @param $id
     *
     * @return Entity|null
     */
    public function findOrNew($id): ?Entity
    {
        return $this->getEntity()->query()->findOrNew($id);
    }

    /**
     * @param $where
     *
     * @return Entity|null
     */
    public function first($where): ?Entity
    {
        return $this->getEntity()->query()->where($where)->first();
    }

    /**
     * @param $data
     *
     * @return Entity|null
     */
    public function firstOrCreate($data): ?Entity
    {
        return $this->getEntity()->query()->firstOrCreate($data);
    }

    /**
     * @param $where
     *
     * @return Entity|null
     */
    public function firstOrNew($where): ?Entity
    {
        return $this->getEntity()->query()->firstOrNew($where);
    }

    /**
     * @param $query
     *
     * @return array|null
     */
    public function firstRaw($query): ?array
    {
        if ($result = DB::select($query)) {
            return $result[0];
        }

        return null;
    }

    /**
     * @param array $values
     * @param array|null $orderBy
     *
     * @return Entity|null
     */
    public function getFirstBetween(array $values, ?array $orderBy = null): ?Entity
    {
        return $this->getBetween($values, $orderBy, 1)->first();
    }

    /**
     * @param array $values
     * @param array|null $orderBy
     * @param int|null $take
     * $orderByExample = [ ["date", "from_date", "to_date"], ["desc", "asc"] ]
     *
     * @return Collection
     */
    public function getBetween(array $values, ?array $orderBy = null, ?int $take = null): Collection
    {
        $entity = $this->getEntity()->query()->whereBetween($values[0], [
            $values[1],
            $values[2],
        ]);

        if ($orderBy) {
            $this->addOrderByToEntity($entity, $orderBy);
        }

        if ($take) {
            $entity = $entity->take($take);
        }

        return $entity->get();
    }

    /**
     * @param array $values
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function getByFieldsIn(array $values, ?array $orderBy = null, ?int $take = null): Collection
    {
        $entity = $this->getEntity();

        foreach ($values as $field => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }

            $entity = $entity->whereIn($field, $value);
        }

        if ($orderBy) {
            $entity = $entity->orderBy($orderBy[0], $orderBy[1]);
        }

        if ($take) {
            $entity = $entity->take($take);
        }

        return $entity->get();
    }

    /**
     * @param            $whereRaw
     * @param array|null $orderBy
     * @param int|null $take
     *
     * @return Collection
     */
    public function getWhereRaw($whereRaw, ?array $orderBy = null, ?int $take = null): Collection
    {
        $entity = $this->getEntity()->whereRaw($whereRaw);

        if ($orderBy) {
            $entity = $entity->orderBy($orderBy[0], $orderBy[1]);
        }

        if ($take) {
            $entity = $entity->take($take);
        }

        return $entity->get();
    }

    /**
     * @param Entity $entity
     */
    abstract public function setEntity(Entity $entity): void;

    /**
     * @param $append
     *
     * @return string
     */
    protected function resolveEventName($append): string
    {
        $reflection = new ReflectionClass($this->getEntity());
        $entityName = $reflection->getShortName();
        $entityPlural = Str::plural($entityName);

        if (Str::endsWith($entityPlural, 'y')) {
            $entityPlural = substr($entityPlural, -1) . 'ies';
        }

        return "Apps\\Default\\Events\\" . $entityPlural . "\\" . $entityName . $append;
    }
}
