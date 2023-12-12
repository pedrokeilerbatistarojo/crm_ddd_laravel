<?php

namespace Domain\Gyms\Services;

use Domain\Gyms\Actions\DeleteGymFeeType;
use Domain\Gyms\Actions\DeleteGymSubscription;
use Domain\Gyms\Actions\DeleteGymSubscriptionMember;
use Domain\Gyms\Actions\DeleteGymSubscriptionMemberAccess;
use Domain\Gyms\Actions\DeleteGymSubscriptionMemberAccessRight;
use Domain\Gyms\Actions\DeleteGymSubscriptionQuota;
use Domain\Gyms\Actions\UpsertGymFeeType;
use Domain\Gyms\Actions\UpsertGymSubscription;
use Domain\Gyms\Actions\UpsertGymSubscriptionMember;
use Domain\Gyms\Actions\UpsertGymSubscriptionMemberAccess;
use Domain\Gyms\Actions\UpsertGymSubscriptionMemberAccessRight;
use Domain\Gyms\Actions\UpsertGymSubscriptionQuota;
use Domain\Gyms\Contracts\Repositories\GymFeeTypesRepository;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionsRepository;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionMembersRepository;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRepository;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRightsRepository;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionQuotasRepository;
use Domain\Gyms\DataTransferObjects\GymFeeTypeEntitiesCollection;
use Domain\Gyms\DataTransferObjects\GymSubscriptionEntitiesCollection;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberEntitiesCollection;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessEntitiesCollection;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightEntitiesCollection;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaEntitiesCollection;
use Domain\Gyms\DataTransferObjects\GymFeeTypeEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightEntity;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaEntity;
use Domain\Gyms\DataTransferObjects\GymFeeTypeSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightSearchRequest;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaSearchRequest;
use Domain\Gyms\DataTransferObjects\GymFeeTypeSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionMemberAccessRightSearchResponse;
use Domain\Gyms\DataTransferObjects\GymSubscriptionQuotaSearchResponse;
use Domain\Gyms\Models\GymFeeType;
use Domain\Gyms\Models\GymSubscription;
use Domain\Gyms\Models\GymSubscriptionMember;
use Domain\Gyms\Models\GymSubscriptionMemberAccess;
use Domain\Gyms\Models\GymSubscriptionMemberAccessRight;
use Domain\Gyms\Models\GymSubscriptionQuota;
use Domain\Gyms\Transformers\GymFeeTypeTransformer;
use Domain\Gyms\Transformers\GymSubscriptionTransformer;
use Domain\Gyms\Transformers\GymSubscriptionMemberTransformer;
use Domain\Gyms\Transformers\GymSubscriptionMemberAccessTransformer;
use Domain\Gyms\Transformers\GymSubscriptionMemberAccessRightTransformer;
use Domain\Gyms\Transformers\GymSubscriptionQuotaTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class GymsService implements \Domain\Gyms\Contracts\Services\GymsService
{
    /**
     * @var GymFeeTypesRepository
     */
    protected GymFeeTypesRepository $gymFeeTypesRepository;

    /**
     * @var GymSubscriptionsRepository
     */
    protected GymSubscriptionsRepository $gymSubscriptionsRepository;

    /**
     * @var GymSubscriptionMembersRepository
     */
    protected GymSubscriptionMembersRepository $gymSubscriptionMembersRepository;

    /**
     * @var GymSubscriptionMemberAccessRepository
     */
    protected GymSubscriptionMemberAccessRepository $gymSubscriptionMemberAccessRepository;

    /**
     * @var GymSubscriptionMemberAccessRightsRepository
     */
    protected GymSubscriptionMemberAccessRightsRepository $gymSubscriptionMemberAccessRightsRepository;

    /**
     * @var GymSubscriptionQuotasRepository
     */
    protected GymSubscriptionQuotasRepository $gymSubscriptionQuotasRepository;


    /**
     * @param GymFeeTypesRepository $gymFeeTypesRepository
     * @param GymSubscriptionsRepository $gymSubscriptionsRepository
     * @param GymSubscriptionMembersRepository $gymSubscriptionMembersRepository
     * @param GymSubscriptionMemberAccessRepository $gymSubscriptionMemberAccessRepository
     * @param GymSubscriptionMemberAccessRightsRepository $gymSubscriptionMemberAccessRightsRepository
     * @param GymSubscriptionQuotasRepository $gymSubscriptionQuotasRepository
     */
    public function __construct(
        GymFeeTypesRepository $gymFeeTypesRepository,
        GymSubscriptionsRepository $gymSubscriptionsRepository,
        GymSubscriptionMembersRepository $gymSubscriptionMembersRepository,
        GymSubscriptionMemberAccessRepository $gymSubscriptionMemberAccessRepository,
        GymSubscriptionMemberAccessRightsRepository $gymSubscriptionMemberAccessRightsRepository,
        GymSubscriptionQuotasRepository $gymSubscriptionQuotasRepository,
    ) {
        $this->gymFeeTypesRepository = $gymFeeTypesRepository;
        $this->gymSubscriptionsRepository = $gymSubscriptionsRepository;
        $this->gymSubscriptionMembersRepository = $gymSubscriptionMembersRepository;
        $this->gymSubscriptionMemberAccessRepository = $gymSubscriptionMemberAccessRepository;
        $this->gymSubscriptionMemberAccessRightsRepository = $gymSubscriptionMemberAccessRightsRepository;
        $this->gymSubscriptionQuotasRepository = $gymSubscriptionQuotasRepository;
    }


    /**
     * @throws UnknownProperties
     */
    public function findGymFeeType(int $id, array $includes = []): ?GymFeeTypeEntity
    {
        if (!$record = $this->gymFeeTypesRepository->find($id)) {
            return null;
        }

        return $this->gymFeeTypeDTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function findGymSubscription(int $id, array $includes = []): ?GymSubscriptionEntity
    {
        if (!$record = $this->gymSubscriptionsRepository->find($id)) {
            return null;
        }

        return $this->gymSubscriptionDTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function findGymSubscriptionMember(int $id, array $includes = []): ?GymSubscriptionMemberEntity
    {
        if (!$record = $this->gymSubscriptionMembersRepository->find($id)) {
            return null;
        }

        return $this->gymSubscriptionMemberDTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function findGymSubscriptionMemberAccess(int $id, array $includes = []): ?GymSubscriptionMemberAccessEntity
    {
        if (!$record = $this->gymSubscriptionMemberAccessRepository->find($id)) {
            return null;
        }

        return $this->gymSubscriptionMemberAccessDTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function findGymSubscriptionMemberAccessRight(int $id, array $includes = []): ?GymSubscriptionMemberAccessRightEntity
    {
        if (!$record = $this->gymSubscriptionMemberAccessRightsRepository->find($id)) {
            return null;
        }

        return $this->gymSubscriptionMemberAccessRightDTOFromModel($record, $includes);
    }

    /**
     * @throws UnknownProperties
     */
    public function findGymSubscriptionQuota(int $id, array $includes = []): ?GymSubscriptionQuotaEntity
    {
        if (!$record = $this->gymSubscriptionQuotasRepository->find($id)) {
            return null;
        }

        return $this->gymSubscriptionQuotaDTOFromModel($record, $includes);
    }


    /**
     * @param array $data
     * @return GymFeeTypeEntity
     * @throws UnknownProperties
     */
    public function createGymFeeType(array $data): GymFeeTypeEntity
    {
        $record = app(UpsertGymFeeType::class)($data);

        return $this->gymFeeTypeDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionEntity
     * @throws UnknownProperties
     */
    public function createGymSubscription(array $data): GymSubscriptionEntity
    {
        $record = app(UpsertGymSubscription::class)($data);

        return $this->gymSubscriptionDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberEntity
     * @throws UnknownProperties
     */
    public function createGymSubscriptionMember(array $data): GymSubscriptionMemberEntity
    {
        $record = app(UpsertGymSubscriptionMember::class)($data);

        return $this->gymSubscriptionMemberDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessEntity
     * @throws UnknownProperties
     */
    public function createGymSubscriptionMemberAccess(array $data): GymSubscriptionMemberAccessEntity
    {
        $record = app(UpsertGymSubscriptionMemberAccess::class)($data);

        return $this->gymSubscriptionMemberAccessDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessRightEntity
     * @throws UnknownProperties
     */
    public function createGymSubscriptionMemberAccessRight(array $data): GymSubscriptionMemberAccessRightEntity
    {
        $record = app(UpsertGymSubscriptionMemberAccessRight::class)($data);

        return $this->gymSubscriptionMemberAccessRightDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionQuotaEntity
     * @throws UnknownProperties
     */
    public function createGymSubscriptionQuota(array $data): GymSubscriptionQuotaEntity
    {
        $record = app(UpsertGymSubscriptionQuota::class)($data);

        return $this->gymSubscriptionQuotaDTOFromModel($record);
    }


    /**
     * @param array $data
     * @return GymFeeTypeEntity
     * @throws UnknownProperties
     */
    public function deleteGymFeeType(array $data): GymFeeTypeEntity
    {
        $record = app(DeleteGymFeeType::class)($data);

        return $this->gymFeeTypeDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionEntity
     * @throws UnknownProperties
     */
    public function deleteGymSubscription(array $data): GymSubscriptionEntity
    {
        $record = app(DeleteGymSubscription::class)($data);

        return $this->gymSubscriptionDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberEntity
     * @throws UnknownProperties
     */
    public function deleteGymSubscriptionMember(array $data): GymSubscriptionMemberEntity
    {
        $record = app(DeleteGymSubscriptionMember::class)($data);

        return $this->gymSubscriptionMemberDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessEntity
     * @throws UnknownProperties
     */
    public function deleteGymSubscriptionMemberAccess(array $data): GymSubscriptionMemberAccessEntity
    {
        $record = app(DeleteGymSubscriptionMemberAccess::class)($data);

        return $this->gymSubscriptionMemberAccessDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessRightEntity
     * @throws UnknownProperties
     */
    public function deleteGymSubscriptionMemberAccessRight(array $data): GymSubscriptionMemberAccessRightEntity
    {
        $record = app(DeleteGymSubscriptionMemberAccessRight::class)($data);

        return $this->gymSubscriptionMemberAccessRightDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionQuotaEntity
     * @throws UnknownProperties
     */
    public function deleteGymSubscriptionQuota(array $data): GymSubscriptionQuotaEntity
    {
        $record = app(DeleteGymSubscriptionQuota::class)($data);

        return $this->gymSubscriptionQuotaDTOFromModel($record);
    }


    /**
     * @param GymFeeTypeSearchRequest $request
     * @return GymFeeTypeSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function searchGymFeeTypes(GymFeeTypeSearchRequest $request): GymFeeTypeSearchResponse
    {
        $query = $this->gymFeeTypesRepository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(GymFeeTypeTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new GymFeeTypeSearchResponse('Ok'))->setData(
            GymFeeTypeEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param GymSubscriptionSearchRequest $request
     * @return GymSubscriptionSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function searchGymSubscriptions(GymSubscriptionSearchRequest $request): GymSubscriptionSearchResponse
    {
        $query = $this->gymSubscriptionsRepository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(GymSubscriptionTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new GymSubscriptionSearchResponse('Ok'))->setData(
            GymSubscriptionEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param GymSubscriptionMemberSearchRequest $request
     * @return GymSubscriptionMemberSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function searchGymSubscriptionMembers(GymSubscriptionMemberSearchRequest $request): GymSubscriptionMemberSearchResponse
    {
        $query = $this->gymSubscriptionMembersRepository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(GymSubscriptionMemberTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new GymSubscriptionMemberSearchResponse('Ok'))->setData(
            GymSubscriptionMemberEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param GymSubscriptionMemberAccessSearchRequest $request
     * @return GymSubscriptionMemberAccessSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function searchGymSubscriptionMemberAccess(GymSubscriptionMemberAccessSearchRequest $request): GymSubscriptionMemberAccessSearchResponse
    {
        $query = $this->gymSubscriptionMemberAccessRepository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(GymSubscriptionMemberAccessTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new GymSubscriptionMemberAccessSearchResponse('Ok'))->setData(
            GymSubscriptionMemberAccessEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param GymSubscriptionMemberAccessRightSearchRequest $request
     * @return GymSubscriptionMemberAccessRightSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function searchGymSubscriptionMemberAccessRights(GymSubscriptionMemberAccessRightSearchRequest $request): GymSubscriptionMemberAccessRightSearchResponse
    {
        $query = $this->gymSubscriptionMemberAccessRightsRepository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(GymSubscriptionMemberAccessRightTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new GymSubscriptionMemberAccessRightSearchResponse('Ok'))->setData(
            GymSubscriptionMemberAccessRightEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param GymSubscriptionQuotaSearchRequest $request
     * @return GymSubscriptionQuotaSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function searchGymSubscriptionQuotas(GymSubscriptionQuotaSearchRequest $request): GymSubscriptionQuotaSearchResponse
    {
        $query = $this->gymSubscriptionQuotasRepository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(GymSubscriptionQuotaTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new GymSubscriptionQuotaSearchResponse('Ok'))->setData(
            GymSubscriptionQuotaEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }


    /**
     * @param array $data
     * @return GymFeeTypeEntity
     * @throws UnknownProperties
     */
    public function updateGymFeeType(array $data): GymFeeTypeEntity
    {
        $record = app(UpsertGymFeeType::class)($data);

        return $this->gymFeeTypeDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionEntity
     * @throws UnknownProperties
     */
    public function updateGymSubscription(array $data): GymSubscriptionEntity
    {
        $record = app(UpsertGymSubscription::class)($data);

        return $this->gymSubscriptionDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberEntity
     * @throws UnknownProperties
     */
    public function updateGymSubscriptionMember(array $data): GymSubscriptionMemberEntity
    {
        $record = app(UpsertGymSubscriptionMember::class)($data);

        return $this->gymSubscriptionMemberDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessEntity
     * @throws UnknownProperties
     */
    public function updateGymSubscriptionMemberAccess(array $data): GymSubscriptionMemberAccessEntity
    {
        $record = app(UpsertGymSubscriptionMemberAccess::class)($data);

        return $this->gymSubscriptionMemberAccessDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionMemberAccessRightEntity
     * @throws UnknownProperties
     */
    public function updateGymSubscriptionMemberAccessRight(array $data): GymSubscriptionMemberAccessRightEntity
    {
        $record = app(UpsertGymSubscriptionMemberAccessRight::class)($data);

        return $this->gymSubscriptionMemberAccessRightDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return GymSubscriptionQuotaEntity
     * @throws UnknownProperties
     */
    public function updateGymSubscriptionQuota(array $data): GymSubscriptionQuotaEntity
    {
        $record = app(UpsertGymSubscriptionQuota::class)($data);

        return $this->gymSubscriptionQuotaDTOFromModel($record);
    }


    /**
     * @param GymFeeType $entity
     * @param array $includes
     * @return GymFeeTypeEntity
     * @throws UnknownProperties
     */
    private function gymFeeTypeDTOFromModel(GymFeeType $entity, array $includes = []): GymFeeTypeEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(GymFeeTypeTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new GymFeeTypeEntity($data);
    }

    /**
     * @param GymSubscription $entity
     * @param array $includes
     * @return GymSubscriptionEntity
     * @throws UnknownProperties
     */
    private function gymSubscriptionDTOFromModel(GymSubscription $entity, array $includes = []): GymSubscriptionEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(GymSubscriptionTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new GymSubscriptionEntity($data);
    }

    /**
     * @param GymSubscriptionMember $entity
     * @param array $includes
     * @return GymSubscriptionMemberEntity
     * @throws UnknownProperties
     */
    private function gymSubscriptionMemberDTOFromModel(GymSubscriptionMember $entity, array $includes = []): GymSubscriptionMemberEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(GymSubscriptionMemberTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new GymSubscriptionMemberEntity($data);
    }

    /**
     * @param GymSubscriptionMemberAccess $entity
     * @param array $includes
     * @return GymSubscriptionMemberAccessEntity
     * @throws UnknownProperties
     */
    private function gymSubscriptionMemberAccessDTOFromModel(GymSubscriptionMemberAccess $entity, array $includes = []): GymSubscriptionMemberAccessEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(GymSubscriptionMemberAccessTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new GymSubscriptionMemberAccessEntity($data);
    }

    /**
     * @param GymSubscriptionMemberAccessRight $entity
     * @param array $includes
     * @return GymSubscriptionMemberAccessRightEntity
     * @throws UnknownProperties
     */
    private function gymSubscriptionMemberAccessRightDTOFromModel(GymSubscriptionMemberAccessRight $entity, array $includes = []): GymSubscriptionMemberAccessRightEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(GymSubscriptionMemberAccessRightTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new GymSubscriptionMemberAccessRightEntity($data);
    }

    /**
     * @param GymSubscriptionQuota $entity
     * @param array $includes
     * @return GymSubscriptionQuotaEntity
     * @throws UnknownProperties
     */
    private function gymSubscriptionQuotaDTOFromModel(GymSubscriptionQuota $entity, array $includes = []): GymSubscriptionQuotaEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(GymSubscriptionQuotaTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new GymSubscriptionQuotaEntity($data);
    }
}
