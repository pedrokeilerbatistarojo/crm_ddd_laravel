<?php

namespace Domain\TreatmentScheduleNotes\Services;

use Domain\TreatmentScheduleNotes\Actions\DeleteTreatmentScheduleNote;
use Domain\TreatmentScheduleNotes\Actions\UpsertTreatmentScheduleNote;
use Domain\TreatmentScheduleNotes\Contracts\Repositories\TreatmentScheduleNotesRepository;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteEntitiesCollection;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteEntity;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteSearchRequest;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteResponse;
use Domain\TreatmentScheduleNotes\Models\TreatmentScheduleNote;
use Domain\TreatmentScheduleNotes\Transformers\TreatmentScheduleNoteTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;

class TreatmentScheduleNotesService implements \Domain\TreatmentScheduleNotes\Contracts\Services\TreatmentScheduleNotesService
{
    /**
     * @var TreatmentScheduleNotesRepository
     */
    protected TreatmentScheduleNotesRepository $repository;

    /**
     * @param TreatmentScheduleNotesRepository $repository
     */
    public function __construct(TreatmentScheduleNotesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?TreatmentScheduleNoteEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param array $data
     * @return TreatmentScheduleNoteEntity
     * @throws UnknownProperties
     */
    public function create(array $data): TreatmentScheduleNoteEntity
    {
        $record = app(UpsertTreatmentScheduleNote::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return TreatmentScheduleNoteEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): TreatmentScheduleNoteEntity
    {
        $record = app(DeleteTreatmentScheduleNote::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param TreatmentScheduleNoteSearchRequest $request
     * @return TreatmentScheduleNoteResponse
     * @throws InvalidDataTypeException
     */
    public function search(TreatmentScheduleNoteSearchRequest $request): TreatmentScheduleNoteResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(TreatmentScheduleNoteTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new TreatmentScheduleNoteResponse('Ok'))->setData(
            TreatmentScheduleNoteEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return TreatmentScheduleNoteEntity
     * @throws UnknownProperties
     */
    public function update(array $data): TreatmentScheduleNoteEntity
    {
        $record = app(UpsertTreatmentScheduleNote::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param TreatmentScheduleNote $entity
     * @param array $includes
     * @return TreatmentScheduleNoteEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(TreatmentScheduleNote $entity, array $includes = []): TreatmentScheduleNoteEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(TreatmentScheduleNoteTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new TreatmentScheduleNoteEntity($data);
    }
}
