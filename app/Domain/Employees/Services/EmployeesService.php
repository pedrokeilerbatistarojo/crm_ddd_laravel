<?php

namespace Domain\Employees\Services;

use Domain\Employees\Actions\DeleteEmployee;
use Domain\Employees\Actions\DeleteEmployeeTimeOff;
use Domain\Employees\Actions\DeleteEmployeeOrder;
use Domain\Employees\Actions\UpsertEmployee;
use Domain\Employees\Actions\UpsertEmployeeTimeOff;
use Domain\Employees\Actions\UpsertEmployeeOrder;
use Domain\Employees\Contracts\Repositories\EmployeesRepository;
use Domain\Employees\DataTransferObjects\EmployeeEntitiesCollection;
use Domain\Employees\DataTransferObjects\EmployeeEntity;
use Domain\Employees\DataTransferObjects\EmployeeOrderEntitiesCollection;
use Domain\Employees\DataTransferObjects\EmployeeOrderEntity;
use Domain\Employees\DataTransferObjects\EmployeeOrderSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeOrderSearchResponse;
use Domain\Employees\DataTransferObjects\EmployeeSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeSearchResponse;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffEntitiesCollection;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffEntity;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffSearchResponse;
use Domain\Employees\Models\Employee;
use Domain\Employees\Models\EmployeeOrder;
use Domain\Employees\Models\EmployeeTimeOff;
use Domain\Employees\Repositories\EmployeeTimeOffRepository;
use Domain\Employees\Repositories\EmployeeOrderRepository;
use Domain\Employees\Transformers\EmployeeOrderTransformer;
use Domain\Employees\Transformers\EmployeeTimeOffTransformer;
use Domain\Employees\Transformers\EmployeeTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\DatabaseException;
use Support\Exceptions\InvalidDataTypeException;

class EmployeesService implements \Domain\Employees\Contracts\Services\EmployeesService
{
    protected EmployeesRepository $repository;
    protected EmployeeTimeOffRepository $repositoryEmployeeTimeOff;
    protected EmployeeOrderRepository $repositoryEmployeeOrder;

    /**
     * @param EmployeesRepository $repository
     * @param EmployeeTimeOffRepository $repositoryEmployeeTimeOff
     * @param EmployeeOrderRepository $repositoryEmployeeOrder
     */
    public function __construct(
        EmployeesRepository $repository,
        EmployeeTimeOffRepository $repositoryEmployeeTimeOff,
        EmployeeOrderRepository $repositoryEmployeeOrder
    ) {
        $this->repository = $repository;
        $this->repositoryEmployeeTimeOff = $repositoryEmployeeTimeOff;
        $this->repositoryEmployeeOrder = $repositoryEmployeeOrder;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?EmployeeEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }
        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return EmployeeEntity
     * @throws UnknownProperties
     */
    public function create(array $data): EmployeeEntity
    {
        $record = app(UpsertEmployee::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return EmployeeEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): EmployeeEntity
    {
        $record = app(DeleteEmployee::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param EmployeeSearchRequest $request
     * @return EmployeeSearchResponse
     * @throws InvalidDataTypeException
     */
    public function search(EmployeeSearchRequest $request): EmployeeSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(EmployeeTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new EmployeeSearchResponse('Ok'))->setData(
            EmployeeEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return EmployeeEntity
     * @throws UnknownProperties
     */
    public function update(array $data): EmployeeEntity
    {
        $record = app(UpsertEmployee::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @throws UnknownProperties
     */
    private function DTOFromModel(Employee $entity, array $includes = []): EmployeeEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(EmployeeTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new EmployeeEntity($data);
    }

    /**
     * @throws UnknownProperties
     */
    private function DTOFromModelEmployeeTimeOff(EmployeeTimeOff $entity, array $includes = []): EmployeeTimeOffEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(EmployeeTimeOffTransformer::class));
        $data = $manager->createData($item)->toArray();
        return new EmployeeTimeOffEntity($data);
    }

    /**
     * @throws UnknownProperties
     */
    private function DTOFromModelEmployeeOrder(EmployeeOrder $entity, array $includes = []): EmployeeOrderEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(EmployeeOrderTransformer::class));
        $data = $manager->createData($item)->toArray();
        return new EmployeeOrderEntity($data);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return EmployeeTimeOffEntity|null
     * @throws UnknownProperties
     */
    public function findEmployeeTimeOff(int $id, array $includes = []): ?EmployeeTimeOffEntity
    {
        if (!$record = $this->repositoryEmployeeTimeOff->find($id)) {
            return null;
        }
        return $this->DTOFromModelEmployeeTimeOff($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return EmployeeOrderEntity|null
     * @throws UnknownProperties
     */
    public function findEmployeeOrder(int $id, array $includes = []): ?EmployeeOrderEntity
    {
        if (!$record = $this->repositoryEmployeeOrder->find($id)) {
            return null;
        }
        return $this->DTOFromModelEmployeeOrder($record, $includes);
    }

    /**
     * @param EmployeeTimeOffSearchRequest $request
     * @return EmployeeTimeOffSearchResponse
     * @throws InvalidDataTypeException
     */
    public function searchEmployeeTimeOff(EmployeeTimeOffSearchRequest $request): EmployeeTimeOffSearchResponse
    {
        $query = $this->repositoryEmployeeTimeOff->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(EmployeeTimeOffTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new EmployeeTimeOffSearchResponse('Ok'))->setData(
            EmployeeTimeOffEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param EmployeeOrderSearchRequest $request
     * @return EmployeeOrderSearchResponse
     * @throws InvalidDataTypeException
     */
    public function searchEmployeeOrder(EmployeeOrderSearchRequest $request): EmployeeOrderSearchResponse
    {
        $query = $this->repositoryEmployeeOrder->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(EmployeeOrderTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new EmployeeOrderSearchResponse('Ok'))->setData(
            EmployeeOrderEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeTimeOffEntity|null
     * @throws UnknownProperties
     */
    public function createEmployeeTimeOff(array $data, array $includes = []): ?EmployeeTimeOffEntity
    {
        $record = app(UpsertEmployeeTimeOff::class)($data);

        return $this->DTOFromModelEmployeeTimeOff($record);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeOrderEntity|null
     * @throws UnknownProperties
     */
    public function createEmployeeOrder(array $data, array $includes = []): ?EmployeeOrderEntity
    {
        $record = app(UpsertEmployeeOrder::class)($data);

        return $this->DTOFromModelEmployeeOrder($record);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeTimeOffEntity|null
     * @throws DatabaseException
     * @throws UnknownProperties
     */
    public function deleteEmployeeTimeOff(array $data, array $includes = []): ?EmployeeTimeOffEntity
    {
        $record = app(DeleteEmployeeTimeOff::class)($data);

        return $this->DTOFromModelEmployeeTimeOff($record);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeOrderEntity|null
     * @throws DatabaseException
     * @throws UnknownProperties
     */
    public function deleteEmployeeOrder(array $data, array $includes = []): ?EmployeeOrderEntity
    {
        $record = app(DeleteEmployeeOrder::class)($data);

        return $this->DTOFromModelEmployeeOrder($record);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeTimeOffEntity|null
     * @throws UnknownProperties
     */
    public function updateEmployeeTimeOff(array $data, array $includes = []): ?EmployeeTimeOffEntity
    {
        $record = app(UpsertEmployeeTimeOff::class)($data);

        return $this->DTOFromModelEmployeeTimeOff($record);
    }

    /**
     * @param array $data
     * @param array $includes
     * @return EmployeeOrderEntity|null
     * @throws UnknownProperties
     */
    public function updateEmployeeOrder(array $data, array $includes = []): ?EmployeeOrderEntity
    {
        $record = app(UpsertEmployeeOrder::class)($data);

        return $this->DTOFromModelEmployeeOrder($record);
    }
}
