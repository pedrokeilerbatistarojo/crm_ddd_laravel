<?php

namespace Domain\Payments\Services;

use Domain\Payments\Actions\DeletePayment;
use Domain\Payments\Actions\UpsertPayment;
use Domain\Payments\Contracts\Repositories\PaymentsRepository;
use Domain\Payments\DataTransferObjects\PaymentEntitiesCollection;
use Domain\Payments\DataTransferObjects\PaymentEntity;
use Domain\Payments\DataTransferObjects\PaymentSearchRequest;
use Domain\Payments\DataTransferObjects\PaymentSearchResponse;
use Domain\Payments\Models\Payment;
use Domain\Payments\Transformers\PaymentTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class PaymentsService implements \Domain\Payments\Contracts\Services\PaymentsService
{
    /**
     * @var PaymentsRepository
     */
    protected PaymentsRepository $repository;

    /**
     * @param PaymentsRepository $repository
     */
    public function __construct(PaymentsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?PaymentEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return PaymentEntity
     * @throws UnknownProperties
     */
    public function create(array $data): PaymentEntity
    {
        $record = app(UpsertPayment::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return PaymentEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): PaymentEntity
    {
        $record = app(DeletePayment::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param PaymentSearchRequest $request
     * @return PaymentSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(PaymentSearchRequest $request): PaymentSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(PaymentTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new PaymentSearchResponse('Ok'))->setData(
            PaymentEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return PaymentEntity
     * @throws UnknownProperties
     */
    public function update(array $data): PaymentEntity
    {
        $record = app(UpsertPayment::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param Payment $entity
     * @param array $includes
     * @return PaymentEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(Payment $entity, array $includes = []): PaymentEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(PaymentTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new PaymentEntity($data);
    }
}
