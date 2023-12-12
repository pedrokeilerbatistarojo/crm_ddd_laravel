<?php

namespace Domain\Clients\Services;

use Domain\Clients\Actions\CreateClientFile;
use Domain\Clients\Actions\CreateClientNote;
use Domain\Clients\Actions\DeleteClient;
use Domain\Clients\Actions\DeleteClientFile;
use Domain\Clients\Actions\DeleteClientNote;
use Domain\Clients\Actions\UpdateClientFile;
use Domain\Clients\Actions\UpdateClientNote;
use Domain\Clients\Actions\UpsertClient;
use Domain\Clients\Contracts\Repositories\ClientFilesRepository;
use Domain\Clients\Contracts\Repositories\ClientNotesRepository;
use Domain\Clients\Contracts\Repositories\ClientsRepository;
use Domain\Clients\DataTransferObjects\ClientDownloadTemplatePdfResponse;
use Domain\Clients\DataTransferObjects\ClientSendConsentEmailResponse;
use Domain\Clients\DataTransferObjects\ClientEntitiesCollection;
use Domain\Clients\DataTransferObjects\ClientEntity;
use Domain\Clients\DataTransferObjects\ClientFileContentResponse;
use Domain\Clients\DataTransferObjects\ClientFileEntitiesCollection;
use Domain\Clients\DataTransferObjects\ClientFileEntity;
use Domain\Clients\DataTransferObjects\ClientFileSearchRequest;
use Domain\Clients\DataTransferObjects\ClientFileSearchResponse;
use Domain\Clients\DataTransferObjects\ClientNoteEntitiesCollection;
use Domain\Clients\DataTransferObjects\ClientNoteEntity;
use Domain\Clients\DataTransferObjects\ClientNoteSearchRequest;
use Domain\Clients\DataTransferObjects\ClientNoteSearchResponse;
use Domain\Clients\DataTransferObjects\ClientSearchRequest;
use Domain\Clients\DataTransferObjects\ClientSearchResponse;
use Domain\Clients\Exports\ClientsExport;
use Domain\Clients\Models\Client;
use Domain\Clients\Models\ClientFile;
use Domain\Clients\Models\ClientNote;
use Domain\Clients\Transformers\ClientFileTransformer;
use Domain\Clients\Transformers\ClientNoteTransformer;
use Domain\Clients\Transformers\ClientTransformer;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Maatwebsite\Excel\Excel;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\DataTransferObjects\Contracts\Response;
use Support\Exceptions\InvalidDataTypeException;
use Support\Exceptions\InvalidStatusException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Domain\Clients\Mails\ClientConsent;
use Exception;

class ClientsService implements \Domain\Clients\Contracts\Services\ClientsService
{
    /**
     * @var ClientsRepository
     */
    protected ClientsRepository $repository;

    /**
     * @var ClientNotesRepository
     */
    protected ClientNotesRepository $clientNotesRepository;

    /**
     * @var ClientFilesRepository
     */
    protected ClientFilesRepository $clientFilesRepository;

    /**
     * @param ClientsRepository $repository
     * @param ClientNotesRepository $clientNotesRepository
     * @param ClientFilesRepository $clientFilesRepository
     */
    public function __construct(
        ClientsRepository $repository,
        ClientNotesRepository $clientNotesRepository,
        ClientFilesRepository $clientFilesRepository
    ) {
        $this->repository = $repository;
        $this->clientNotesRepository = $clientNotesRepository;
        $this->clientFilesRepository = $clientFilesRepository;
    }

    /**
     * @param array $data
     * @return ClientEntity
     * @throws UnknownProperties
     */
    public function create(array $data): ClientEntity
    {
        $record = app(UpsertClient::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientFileEntity
     * @throws UnknownProperties
     */
    public function createFile(array $data): ClientFileEntity
    {
        $record = app(CreateClientFile::class)($data);

        return $this->clientFileDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientNoteEntity
     * @throws UnknownProperties
     */
    public function createNote(array $data): ClientNoteEntity
    {
        $record = app(CreateClientNote::class)($data);

        return $this->clientNoteDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): ClientEntity
    {
        $record = app(DeleteClient::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientFileEntity
     * @throws UnknownProperties
     */
    public function deleteFile(array $data): ClientFileEntity
    {
        $record = app(DeleteClientFile::class)($data);

        return $this->clientFileDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientNoteEntity
     * @throws UnknownProperties
     */
    public function deleteNote(array $data): ClientNoteEntity
    {
        $record = app(DeleteClientNote::class)($data);

        return $this->clientNoteDTOFromModel($record);
    }

    /**
     * @param ClientSearchRequest $request
     * @return string|null
     * @throws InvalidDataTypeException
     * @throws UnknownProperties
     */
    public function exportExcel(ClientSearchRequest $request): ?string
    {
        $request->paginateSize = config('system.infinite_pagination');
        $records = $this->search($request);
        $excel = new ClientsExport($records->getData());
        $name = uniqid() . '.xlsx';
        app(Excel::class)->store($excel, $name);

        return $name;
    }

    /**
     * @param ClientSearchRequest $request
     * @return ClientSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function duplicates(ClientSearchRequest $request): ClientSearchResponse
    {
        $query = $this->repository->duplicatesQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ClientTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ClientSearchResponse('Ok'))->setData(
            ClientEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param int $id
     * @return ClientFileContentResponse
     * @throws UnknownProperties
     */
    public function fileContent(int $id): ClientFileContentResponse
    {
        $file = $this->clientFileDTOFromModel(
            $this->clientFilesRepository->find($id),
            []
        );

        return app(ClientFileContentResponse::class, ['status' => Response::STATUSES['OK']])
            ->setData(
                [
                    'title' => $file->file,
                    'content' => base64_encode(Storage::disk('local')->get('public/'.$file->file))
                ]
            );
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?ClientEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return ClientFileEntity|null
     * @throws UnknownProperties
     */
    public function findFile(int $id, array $includes = []): ?ClientFileEntity
    {
        if (!$record = $this->clientFilesRepository->find($id)) {
            return null;
        }

        return $this->clientFileDTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return ClientNoteEntity|null
     * @throws UnknownProperties
     */
    public function findNote(int $id, array $includes = []): ?ClientNoteEntity
    {
        if (!$record = $this->clientNotesRepository->find($id)) {
            return null;
        }

        return $this->clientNoteDTOFromModel($record, $includes);
    }

    /**
     * @param ClientSearchRequest $request
     * @return ClientSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(ClientSearchRequest $request): ClientSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ClientTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ClientSearchResponse('Ok'))->setData(
            ClientEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }



    /**
     * @param ClientFileSearchRequest $request
     * @return ClientFileSearchResponse
     * @throws InvalidDataTypeException
     * @throws UnknownProperties
     */
    public function searchFiles(ClientFileSearchRequest $request): ClientFileSearchResponse
    {
        $query = $this->clientFilesRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ClientFileTransformer::class), 'data');

        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ClientFileSearchResponse('Ok'))->setData(
            ClientFileEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param ClientNoteSearchRequest $request
     * @return ClientNoteSearchResponse
     * @throws InvalidDataTypeException
     * @throws UnknownProperties
     */
    public function searchNotes(ClientNoteSearchRequest $request): ClientNoteSearchResponse
    {
        $query = $this->clientNotesRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(ClientNoteTransformer::class), 'data');

        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new ClientNoteSearchResponse('Ok'))->setData(
            ClientNoteEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return ClientEntity
     * @throws UnknownProperties
     */
    public function update(array $data): ClientEntity
    {
        $record = app(UpsertClient::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientFileEntity
     * @throws UnknownProperties
     */
    public function updateFile(array $data): ClientFileEntity
    {
        $record = app(UpdateClientFile::class)($data);

        return $this->clientFileDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return ClientNoteEntity
     * @throws UnknownProperties
     */
    public function updateNote(array $data): ClientNoteEntity
    {
        $record = app(UpdateClientNote::class)($data);

        return $this->clientNoteDTOFromModel($record);
    }

    /**
     * @param ClientFile $entity
     * @param array $includes
     * @return ClientFileEntity
     * @throws UnknownProperties
     */
    protected function clientFileDTOFromModel(ClientFile $entity, array $includes = []): ClientFileEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ClientFileTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ClientFileEntity($data);
    }

    /**
     * @param ClientNote $entity
     * @param array $includes
     * @return ClientNoteEntity
     * @throws UnknownProperties
     */
    protected function clientNoteDTOFromModel(ClientNote $entity, array $includes = []): ClientNoteEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ClientNoteTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ClientNoteEntity($data);
    }

    /**
     * @param Client $entity
     * @param array $includes
     * @return ClientEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(Client $entity, array $includes = []): ClientEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(ClientTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new ClientEntity($data);
    }

    /**
     * @param Client $client
     * @return ClientDownloadTemplatePdfResponse
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    public function downloadTemplate(Client $client): ClientDownloadTemplatePdfResponse
    {
        $pdf = Pdf::loadView('pdf.client_download_template', [
            'data' => [
                'client' => $client
            ]
        ]);

        return (new ClientDownloadTemplatePdfResponse(Response::STATUSES['OK']))
            ->setSuccess()
            ->setData(
                [
                    'title' => 'CONSENTIMIENTO EXPRESO CLIENTES THERMAS DE GRIÑÓN',
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param int $id
     * @param string $email
     * @return ClientSendConsentEmailResponse
     * @throws InvalidStatusException
     */
    public function sendConsentEmail(int $id, string $email): ClientSendConsentEmailResponse
    {
        $response = app(ClientSendConsentEmailResponse::class, ['status' => 'Ok']);

        try {
            Mail::to($email)->send(
                new ClientConsent($id, $this)
            );
            $response->setSuccess();
        } catch (Exception $ex) {
            logger($ex);
            $response->setErrors([$ex->getMessage()]);
        }

        return $response;
    }
}
