<?php

namespace Domain\Invoices\Services;

use Domain\Invoices\Actions\DeleteInvoice;
use Domain\Invoices\Actions\UpsertInvoice;
use Domain\Invoices\Contracts\Repositories\InvoicesRepository;
use Domain\Invoices\DataTransferObjects\InvoiceEntitiesCollection;
use Domain\Invoices\DataTransferObjects\InvoiceEntity;
use Domain\Invoices\DataTransferObjects\InvoiceSearchRequest;
use Domain\Invoices\DataTransferObjects\InvoiceSearchResponse;
use Domain\Invoices\Models\Invoice;
use Domain\Invoices\Transformers\InvoiceTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class InvoicesService implements \Domain\Invoices\Contracts\Services\InvoicesService
{
    /**
     * @var InvoicesRepository
     */
    protected InvoicesRepository $repository;

    /**
     * @param InvoicesRepository $repository
     */
    public function __construct(InvoicesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?InvoiceEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return InvoiceEntity
     * @throws UnknownProperties
     */
    public function create(array $data): InvoiceEntity
    {
        $record = app(UpsertInvoice::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return InvoiceEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): InvoiceEntity
    {
        $record = app(DeleteInvoice::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param InvoiceSearchRequest $request
     * @return InvoiceSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(InvoiceSearchRequest $request): InvoiceSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(InvoiceTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new InvoiceSearchResponse('Ok'))->setData(
            InvoiceEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return InvoiceEntity
     * @throws UnknownProperties
     */
    public function update(array $data): InvoiceEntity
    {
        $record = app(UpsertInvoice::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param Invoice $entity
     * @param array $includes
     * @return InvoiceEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(Invoice $entity, array $includes = []): InvoiceEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(InvoiceTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new InvoiceEntity($data);
    }
}
