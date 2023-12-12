<?php

namespace Domain\Discounts\Services;

use Domain\Discounts\Actions\DeleteDiscount;
use Domain\Discounts\Actions\UpsertDiscount;
use Domain\Discounts\Contracts\Repositories\DiscountsRepository;
use Domain\Discounts\DataTransferObjects\DiscountEntitiesCollection;
use Domain\Discounts\DataTransferObjects\DiscountEntity;
use Domain\Discounts\DataTransferObjects\DiscountSearchRequest;
use Domain\Discounts\DataTransferObjects\DiscountSearchResponse;
use Domain\Discounts\Models\Discount;
use Domain\Discounts\Transformers\DiscountTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class DiscountsService implements \Domain\Discounts\Contracts\Services\DiscountsService
{
    /**
     * @var DiscountsRepository
     */
    protected DiscountsRepository $repository;

    /**
     * @param DiscountsRepository $repository
     */
    public function __construct(DiscountsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?DiscountEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return DiscountEntity
     * @throws UnknownProperties
     */
    public function create(array $data): DiscountEntity
    {
        $record = app(UpsertDiscount::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return DiscountEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): DiscountEntity
    {
        $record = app(DeleteDiscount::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param DiscountSearchRequest $request
     * @return DiscountSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(DiscountSearchRequest $request): DiscountSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(DiscountTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new DiscountSearchResponse('Ok'))->setData(
            DiscountEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return DiscountEntity
     * @throws UnknownProperties
     */
    public function update(array $data): DiscountEntity
    {
        $record = app(UpsertDiscount::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param Discount $entity
     * @param array $includes
     * @return DiscountEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(Discount $entity, array $includes = []): DiscountEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(DiscountTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new DiscountEntity($data);
    }
}
