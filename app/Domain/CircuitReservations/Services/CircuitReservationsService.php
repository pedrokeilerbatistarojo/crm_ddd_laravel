<?php

namespace Domain\CircuitReservations\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\CircuitReservations\Actions\CreateCircuitReservation;
use Domain\CircuitReservations\Actions\DeleteCircuitReservation;
use Domain\CircuitReservations\Actions\MarkAsUsedCircuitReservation;
use Domain\CircuitReservations\Actions\UpdateCircuitReservation;
use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationOrderDetailsRepository;
use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationsRepository;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationEntitiesCollection;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationEntity;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSchedulesPdfResponse;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSearchRequest;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSearchResponse;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSendUpcomingReservationEmailResponse;
use Domain\CircuitReservations\Mails\MailReservation;
use Domain\CircuitReservations\Mails\UpcomingReservation;
use Domain\CircuitReservations\Models\CircuitReservation;
use Domain\CircuitReservations\Transformers\CircuitReservationTransformer;
use Domain\Clients\Contracts\Services\ClientsService;
use Domain\Orders\Contracts\Services\OrdersService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Enums\SQLSort;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\DataTransferObjects\Contracts\Response;
use Support\Exceptions\InvalidDataTypeException;
use Support\Exceptions\InvalidStatusException;
use Support\Helpers\DatesHelper;

class CircuitReservationsService implements \Domain\CircuitReservations\Contracts\Services\CircuitReservationsService
{
    /**
     * @param CircuitReservationsRepository $repository
     * @param CircuitReservationOrderDetailsRepository $circuitReservationOrderDetailsRepository
     * @param ClientsService $clientsService
     * @param OrdersService $ordersService
     */
    public function __construct(
        protected readonly CircuitReservationsRepository $repository,
        protected readonly CircuitReservationOrderDetailsRepository $circuitReservationOrderDetailsRepository,
        protected readonly ClientsService $clientsService,
        protected readonly OrdersService $ordersService
    ) {
    }

    /**
     * @param array $data
     * @return CircuitReservationEntity
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    public function create(array $data): CircuitReservationEntity
    {
        $record = app(CreateCircuitReservation::class)($data);
        $this->isNotifyEmail($data, $record);
        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return CircuitReservationEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): CircuitReservationEntity
    {
        $record = app(DeleteCircuitReservation::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return CircuitReservationEntity|null
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?CircuitReservationEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return CircuitReservationSearchResponse
     * @throws InvalidDataTypeException
     */
    public function findByOrderDetail(int $id, array $includes = []): CircuitReservationSearchResponse
    {
        $ids = $this->circuitReservationOrderDetailsRepository->getEntity()->query()
            ->where('order_detail_id', $id)
            ->get()
            ->pluck('id')
            ->toArray();

        if (!count($ids)) {
            $ids = ['0'];
        }

        $query = $this->repository->searchQueryBuilder(['id' => $ids], 'id', SQLSort::SORT_ASC);
        $records = $query->paginate(config('system.infinite_pagination'));
        $collection = new Collection($records->items(), app(CircuitReservationTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new CircuitReservationSearchResponse('Ok'))->setData(
            CircuitReservationEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return CircuitReservationEntity
     * @throws UnknownProperties
     */
    public function markAsUsed(array $data): CircuitReservationEntity
    {
        $record = app(MarkAsUsedCircuitReservation::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param string $date
     * @return CircuitReservationSchedulesPdfResponse
     * @throws InvalidDataTypeException
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    public function schedulesPdf(string $date): CircuitReservationSchedulesPdfResponse
    {
        $searchRequest = app(CircuitReservationSearchRequest::class, [
            'args' => [
                'filters' => ['date' => $date],
                'includes' => ['client', 'orderDetails', 'orderDetails.product', 'orderDetails.product.productType','orderDetails.order'],
                'paginateSize' => config('system.infinite_pagination'),
                'sortField' => 'time',
                'sortType' => SQLSort::SORT_ASC
            ]
        ]);

        $records = $this->search($searchRequest);
        $schedules = collect(DatesHelper::dateSchedules($date))->chunk(3);
        $date = Carbon::parse($date);
        $pdf = Pdf::loadView('pdf.circuit_reservations_schedules', [
            'data' => [
                'records' => $records->getData()->sortBy(fn ($item) => strtotime($item->time)),
                'schedules' => $schedules,
                'dateNormal' => $date,
                'dayOfWeek' => $date->dayOfWeek,
                'date' => DatesHelper::spanishWeekDay(
                    $date->weekday()
                ) . ', ' . $date->day . ' de ' . DatesHelper::spanishMonthName($date->month) . ' del ' . $date->year
            ]
        ]);

        return (new CircuitReservationSchedulesPdfResponse(Response::STATUSES['OK']))
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Reservas - ' . $date,
                    'content' => base64_encode($pdf->save('test.pdf')->output())
                ]
            );
    }

    /**
     * @param CircuitReservationSearchRequest $request
     *
     * @return CircuitReservationSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(CircuitReservationSearchRequest $request): CircuitReservationSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(CircuitReservationTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new CircuitReservationSearchResponse('Ok'))->setData(
            CircuitReservationEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param int $id string $email
     */
    public function sendUpcomingReservationEmail(int $id): CircuitReservationSendUpcomingReservationEmailResponse
    {
        $response = app(CircuitReservationSendUpcomingReservationEmailResponse::class, ['status' => 'Ok']);

        try {
            Mail::send(
                new UpcomingReservation($id, $this)
            );
            $response->setSuccess();
        } catch (Exception $ex) {
            logger($ex);
            $response->setErrors([$ex->getMessage()]);
        }

        return $response;
    }

    /**
     * @param array $data
     * @return CircuitReservationEntity
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    public function update(array $data): CircuitReservationEntity
    {
        $record = app(UpdateCircuitReservation::class)($data);
        $this->isNotifyEmail($data, $record);
        return $this->DTOFromModel($record);
    }

    /**
     * @param CircuitReservation $entity
     * @param array $includes
     * @return CircuitReservationEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(CircuitReservation $entity, array $includes = []): CircuitReservationEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(CircuitReservationTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new CircuitReservationEntity($data);
    }

    /**
     * @param array $data
     * @return CircuitReservationSendUpcomingReservationEmailResponse
     * @throws InvalidStatusException
     */
    public function sendEmail(array $data): CircuitReservationSendUpcomingReservationEmailResponse
    {
        $response = app(CircuitReservationSendUpcomingReservationEmailResponse::class, ['status' => 'Ok']);

        try {
            Mail::send(
                new MailReservation($data, $this)
            );
            $response->setSuccess();
        } catch (Exception $ex) {
            logger($ex);
            $response->setErrors([$ex->getMessage()]);
        }

        return $response;
    }

    /**
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    private function isNotifyEmail($data, $record)
    {
        if (isset($data['notify']) && $data['notify']) {
            $record = $this->find($record->id, ['client','orderDetails','orderDetails.order','orderDetails.product']);
            $dataEmail = [
                'id' => $record->id,
                'email' => $record->client->email
            ];
            $this->sendEmail($dataEmail);
        }
    }
}
