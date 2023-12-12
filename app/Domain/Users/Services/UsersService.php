<?php

namespace Domain\Users\Services;

use Domain\Users\Actions\DeleteUser;
use Domain\Users\Actions\UpsertUser;
use Domain\Users\Contracts\Repositories\UsersRepository;
use Domain\Users\DataTransferObjects\UserEntitiesCollection;
use Domain\Users\DataTransferObjects\UserEntity;
use Domain\Users\DataTransferObjects\UserSearchRequest;
use Domain\Users\DataTransferObjects\UserSearchResponse;
use Domain\Users\Models\User;
use Domain\Users\Transformers\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class UsersService implements \Domain\Users\Contracts\Services\UsersService
{
    /**
     * @var UsersRepository
     */
    protected UsersRepository $repository;

    /**
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?UserEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return UserEntity
     * @throws UnknownProperties
     */
    public function create(array $data): UserEntity
    {
        $record = app(UpsertUser::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return UserEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): UserEntity
    {
        $record = app(DeleteUser::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param UserSearchRequest $request
     * @return UserSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(UserSearchRequest $request): UserSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(UserTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new UserSearchResponse('Ok'))->setData(
            UserEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return UserEntity
     * @throws UnknownProperties
     */
    public function update(array $data): UserEntity
    {
        $record = app(UpsertUser::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param User $entity
     * @param array $includes
     * @return UserEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(User $entity, array $includes = []): UserEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(UserTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new UserEntity($data);
    }
}
