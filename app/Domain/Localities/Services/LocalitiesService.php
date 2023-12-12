<?php

namespace Domain\Localities\Services;

use Domain\Localities\Contracts\Repositories\LocalitiesRepository;
use Domain\Localities\Contracts\Repositories\ProvincesRepository;
use Domain\Localities\DataTransferObjects\LocalityEntitiesCollection;
use Domain\Localities\DataTransferObjects\LocalityEntity;
use Domain\Localities\DataTransferObjects\LocalitySearchRequest;
use Domain\Localities\DataTransferObjects\LocalitySearchResponse;
use Domain\Localities\DataTransferObjects\ProvinceEntitiesCollection;
use Domain\Localities\DataTransferObjects\ProvinceEntity;
use Domain\Localities\DataTransferObjects\ProvinceSearchRequest;
use Domain\Localities\DataTransferObjects\ProvinceSearchResponse;
use Domain\Localities\Models\Locality;
use Domain\Localities\Models\Province;
use Domain\Localities\Transformers\LocalityTransformer;
use Domain\Localities\Transformers\ProvinceTransformer;
use Domain\Localities\Actions\UpsertLocality;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class LocalitiesService implements \Domain\Localities\Contracts\Services\LocalitiesService
{
    /**
     * @var LocalitiesRepository
     */
    protected LocalitiesRepository $repository;

    /**
     * @var ProvincesRepository
     */
    protected ProvincesRepository $provinceRepository;

    /**
     * @param LocalitiesRepository $repository
     * @param ProvincesRepository $provinceRepository
     */
    public function __construct(
        LocalitiesRepository $repository,
        ProvincesRepository $provinceRepository,
    ) {
        $this->repository = $repository;
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * @param array $data
     * @param array $includes
     * @return LocalityEntity
     * @throws UnknownProperties
     */
    public function createLocality(array $data, array $includes = []): LocalityEntity
    {
        $record = app(UpsertLocality::class)($data);

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?LocalityEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function findProvince(int $id, array $includes = []): ?ProvinceEntity
    {
        if (!$record = $this->provinceRepository->find($id)) {
            return null;
        }

        return $this->DTOFromProvinceModel($record, $includes);
    }

    /**
     * @param ProvinceSearchRequest $request
     * @return ProvinceSearchResponse
     * @throws InvalidDataTypeException
     */
    public function provinces(ProvinceSearchRequest $request): ProvinceSearchResponse
    {
        $query = $this->provinceRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ProvinceTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ProvinceSearchResponse('Ok'))->setData(
            ProvinceEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param LocalitySearchRequest $request
     * @return LocalitySearchResponse
     * @throws InvalidDataTypeException
     */
    public function search(LocalitySearchRequest $request): LocalitySearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(LocalityTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new LocalitySearchResponse('Ok'))->setData(
            LocalityEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @throws UnknownProperties
     */
    private function DTOFromModel(Locality $entity, array $includes = []): LocalityEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(LocalityTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new LocalityEntity($data);
    }

    /**
     * @throws UnknownProperties
     */
    private function DTOFromProvinceModel(Province $entity, array $includes = []): ProvinceEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ProvinceTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ProvinceEntity($data);
    }
}
