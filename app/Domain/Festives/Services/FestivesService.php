<?php

namespace Domain\Festives\Services;

use Domain\Festives\Actions\DeleteFestive;
use Domain\Festives\Actions\UpsertFestive;
use Domain\Festives\Contracts\Repositories\FestivesRepository;
use Domain\Festives\DataTransferObjects\FestiveEntitiesCollection;
use Domain\Festives\DataTransferObjects\FestiveEntity;
use Domain\Festives\DataTransferObjects\FestiveSearchRequest;
use Domain\Festives\DataTransferObjects\FestiveSearchResponse;
use Domain\Festives\Models\Festive;
use Domain\Festives\Transformers\FestiveTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class FestivesService implements \Domain\Festives\Contracts\Services\FestivesService
{
    /**
     * @var FestivesRepository
     */
    protected FestivesRepository $repository;

    /**
     * @param FestivesRepository $repository
     */
    public function __construct(FestivesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?FestiveEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return FestiveEntity
     * @throws UnknownProperties
     */
    public function create(array $data): FestiveEntity
    {
        $record = app(UpsertFestive::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return FestiveEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): FestiveEntity
    {
        $record = app(DeleteFestive::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param FestiveSearchRequest $request
     * @return FestiveSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(FestiveSearchRequest $request): FestiveSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(FestiveTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new FestiveSearchResponse('Ok'))->setData(
            FestiveEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return FestiveEntity
     * @throws UnknownProperties
     */
    public function update(array $data): FestiveEntity
    {
        $record = app(UpsertFestive::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param Festive $entity
     * @param array $includes
     * @return FestiveEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(Festive $entity, array $includes = []): FestiveEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(FestiveTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new FestiveEntity($data);
    }
}
