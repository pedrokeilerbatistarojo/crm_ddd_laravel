<?php

namespace Domain\TreatmentReservations\Services;

use Domain\Clients\Contracts\Services\ClientsService;
use Domain\Employees\Contracts\Services\EmployeesService;
use Domain\Employees\DataTransferObjects\EmployeeEntitiesCollection;
use Domain\Employees\DataTransferObjects\EmployeeOrderSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeSearchRequest;
use Domain\Employees\DataTransferObjects\EmployeeSearchResponse;
use Domain\Employees\DataTransferObjects\EmployeeTimeOffSearchRequest;
use Domain\Orders\Contracts\Services\OrdersService;
use Domain\TreatmentReservations\Actions\CreateTreatmentReservation;
use Domain\TreatmentReservations\Actions\DeleteTreatmentReservation;
use Domain\TreatmentReservations\Actions\MarkAsUsedTreatmentReservation;
use Domain\TreatmentReservations\Actions\UpdateTreatmentReservation;
use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationOrderDetailsRepository;
use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationsRepository;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationEntitiesCollection;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationEntity;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSearchRequest;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSearchResponse;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSendUpcomingReservationEmailResponse;
use Domain\TreatmentReservations\Mails\MailReservation;
use Domain\TreatmentReservations\Mails\UpcomingReservation;
use Domain\TreatmentReservations\Models\TreatmentReservation;
use Domain\TreatmentReservations\Transformers\TreatmentReservationTransformer;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSchedulesPdfResponse;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteSearchRequest;
use Domain\TreatmentScheduleNotes\Services\TreatmentScheduleNotesService;
use Exception;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Enums\SQLSort;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\Exceptions\InvalidDataTypeException;
use Support\Exceptions\InvalidStatusException;
use Support\DataTransferObjects\Contracts\Response;
use Support\Helpers\DatesHelper;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Employees\Models\Employee;
use Symfony\Component\HttpFoundation\Request;

class TreatmentReservationsService implements
    \Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService
{
    /**
     * @param TreatmentReservationsRepository $repository
     * @param TreatmentReservationOrderDetailsRepository $treatmentReservationOrderDetailsRepository
     * @param EmployeesService $employeesService
     * @param ClientsService $clientsService
     * @param OrdersService $ordersService
     * @param TreatmentScheduleNotesService $treatmentScheduleNotesService
     */
    public function __construct(
        protected readonly TreatmentReservationsRepository $repository,
        protected readonly TreatmentReservationOrderDetailsRepository $treatmentReservationOrderDetailsRepository,
        protected readonly EmployeesService $employeesService,
        protected readonly ClientsService $clientsService,
        protected readonly OrdersService $ordersService,
        protected readonly TreatmentScheduleNotesService $treatmentScheduleNotesService
    ) {
    }

    /**
     * @param array $data
     * @return TreatmentReservationEntity
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    public function create(array $data): TreatmentReservationEntity
    {
        $record = app(CreateTreatmentReservation::class)($data);
        $this->isNotifyEmail($data, $record);
        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return TreatmentReservationEntity
     * @throws UnknownProperties
     */
    public function markAsUsed(array $data): TreatmentReservationEntity
    {
        $record = app(MarkAsUsedTreatmentReservation::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return TreatmentReservationEntity|null
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?TreatmentReservationEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return TreatmentReservationSearchResponse
     * @throws InvalidDataTypeException
     */
    public function findByOrderDetail(int $id, array $includes = []): TreatmentReservationSearchResponse
    {
        $ids = $this->treatmentReservationOrderDetailsRepository->getEntity()->query()
            ->where('order_detail_id', $id)
            ->get()
            ->pluck('id')
            ->toArray();

        if (!count($ids)) {
            $ids = ['0'];
        }

        $records = $this->repository->searchQueryBuilder(['id' => $ids], 'id', SQLSort::SORT_ASC)->paginate(
            config('system.infinite_pagination')
        );
        $collection = new Collection($records->items(), app(TreatmentReservationTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new TreatmentReservationSearchResponse('Ok'))->setData(
            TreatmentReservationEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return TreatmentReservationEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): TreatmentReservationEntity
    {
        $record = app(DeleteTreatmentReservation::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return TreatmentReservationEntity
     * @throws UnknownProperties
     * @throws InvalidStatusException
     */
    public function update(array $data): TreatmentReservationEntity
    {
        $record = app(UpdateTreatmentReservation::class)($data);
        $this->isNotifyEmail($data, $record);
        return $this->DTOFromModel($record);
    }

    /**
     * @param TreatmentReservationSearchRequest $request
     * @return TreatmentReservationsearchResponse
     * @throws InvalidDataTypeException
     */
    public function search(TreatmentReservationSearchRequest $request): TreatmentReservationSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(TreatmentReservationTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);
        $result = $resourceCollection->toArray();
        return (new TreatmentReservationSearchResponse('Ok'))->setData(
            TreatmentReservationEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param int $id string $email
     * @throws InvalidStatusException
     */
    public function sendUpcomingReservationEmail(int $id): TreatmentReservationSendUpcomingReservationEmailResponse
    {
        $response = app(TreatmentReservationSendUpcomingReservationEmailResponse::class, ['status' => 'Ok']);

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
     * @param TreatmentReservation $entity
     * @param array $includes
     * @return TreatmentReservationEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(TreatmentReservation $entity, array $includes = []): TreatmentReservationEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(TreatmentReservationTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new TreatmentReservationEntity($data);
    }

    /**
     * @param string $date
     * @param Request $request
     * @return TreatmentReservationSchedulesPdfResponse
     * @throws InvalidDataTypeException
     * @throws InvalidStatusException
     * @throws UnknownProperties
     */
    public function schedulesPdf(string $date, Request $request): TreatmentReservationSchedulesPdfResponse
    {
        $searchRequest = app(TreatmentReservationSearchRequest::class, [
            'args' => [
                'filters' => ['date' => $date],
                'includes' => ['client', 'orderDetails', 'orderDetails.order'],
                'paginateSize' => config('system.infinite_pagination'),
                'sortField' => 'time',
                'sortType' => SQLSort::SORT_ASC
            ]
        ]);

        $employees = $this->employeesService->search(
            new EmployeeSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination')
            ])
        );

        $employeesTimeOff = $this->employeesService->searchEmployeeTimeOff(
            new EmployeeTimeOffSearchRequest([
                'filters' => [
                    'type' => 'Día Completo',
                    'from_date_from' => $date . ' 00:00:00',
                    'from_date_to' => $date . ' 23:59:59',
                ],
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination'),
            ])
        );

        $employeesOrder = $this->employeesService->searchEmployeeOrder(
            new EmployeeOrderSearchRequest([
                'filters' => [
                    'date' => $date,
                ],
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination'),
            ])
        );

        if ($employeesOrder->getData()->count() > 0) {
            $arrEmployees = [];
            $employeeOrder = $employeesOrder->getData()->first();
            $orders = json_decode($employeeOrder->order);
            foreach ($orders as $orderEmployee) {
                $obj = $employees->getData()->firstWhere('id', '=', $orderEmployee->id);
                if (!empty($obj)) {
                    $arrEmployees[] = $obj->toArray();
                }
            }
            if (count($arrEmployees) > 0) {
                $employeesCollection = EmployeeEntitiesCollection::make($arrEmployees);
                $employees = (new EmployeeSearchResponse('Ok'))->setData(
                    EmployeeEntitiesCollection::createFromArray($employeesCollection->all())
                );
            }
        }

        $employeesData = $employees->getData()
            ->filter(function ($employee) use ($employeesTimeOff) {
                $pass = true;
                foreach ($employeesTimeOff->getData() as $timeOff) {
                    if ($timeOff->employee_id === $employee->id) {
                        $pass = false;
                    }
                }
                return $pass;
            });

        $employeesTimeOff = [];
        $treatmentScheduleNotes = [];

        foreach ($employeesData as $employee) {
            $timeOffObject = $this->employeesService->searchEmployeeTimeOff(
                new EmployeeTimeOffSearchRequest([
                    'filters' => [
                        'employee_id' => $employee->id,
                        'from_date_from' => "{$date} 00:00:00",
                        'from_date_to' => "{$date} 23:59:59"
                    ],
                    'includes' => explode(',', $request->get('includes', '')),
                    'paginateSize' => config('system.infinite_pagination')
                ])
            );
            if (!empty($timeOffObject)) {
                $employeesTimeOff[$employee->id] = $timeOffObject->getData();
            }
        }

        foreach ($employeesData as $employee) {
            $treatmentScheduleNote = $this->treatmentScheduleNotesService->search(
                new TreatmentScheduleNoteSearchRequest([
                    'filters' => [
                        'employee_id' => $employee->id,
                        'date_from' => "{$date} 00:00:00",
                        'date_to' => "{$date} 23:59:59"
                    ],
                    'includes' => explode(',', $request->get('includes', '')),
                    'paginateSize' => config('system.infinite_pagination')
                ])
            );
            if (!empty($treatmentScheduleNote)) {
                $treatmentScheduleNotes[$employee->id] = $treatmentScheduleNote->getData();
            }
        }

        $records = $this->search($searchRequest);
        $schedules = collect(DatesHelper::dateTreatmentSchedules($date))->all();
        $date = Carbon::parse($date);

        $pdf = Pdf::loadView('pdf.treatment_reservations_schedules', [
            'data' => [
                'records' => $records->getData()->sortBy(fn ($item) => strtotime($item->time)),
                'schedules' => $schedules,
                'employees' => $employeesData,
                'employeesTimeOff' => $employeesTimeOff,
                'treatmentScheduleNotes' => $treatmentScheduleNotes,
                'date' => DatesHelper::spanishWeekDay(
                    $date->weekday()
                ) . ', ' . $date->format('d/m/Y')
//                'date' => DatesHelper::spanishWeekDay(
//                    $date->weekday()
//                ) . ', ' . $date->day . ' de ' . DatesHelper::spanishMonthName($date->month) . ' del ' . $date->year
            ]
        ])->setPaper('a4', 'landscape');

        return (new TreatmentReservationSchedulesPdfResponse(Response::STATUSES['OK']))
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Reservas - ' . $date,
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param string $date
     * @param Employee $employee
     * @param Request $request
     * @return TreatmentReservationSchedulesPdfResponse
     * @throws InvalidDataTypeException
     * @throws InvalidStatusException
     * @throws UnknownProperties
     */
    public function schedulesEmployeePdf(string $date, Employee $employee, Request $request): TreatmentReservationSchedulesPdfResponse
    {
        $searchRequest = app(TreatmentReservationSearchRequest::class, [
            'args' => [
                'filters' => ['date' => $date],
                'includes' => ['client', 'orderDetails', 'orderDetails.order'],
                'paginateSize' => config('system.infinite_pagination'),
                'sortField' => 'time',
                'sortType' => SQLSort::SORT_ASC
            ]
        ]);

        $employeesTimeOff = $this->employeesService->searchEmployeeTimeOff(
            new EmployeeTimeOffSearchRequest([
                'filters' => [
                    'employee_id' => $employee->id,
                    'from_date_from' => "{$date} 00:00:00",
                    'from_date_to' => "{$date} 23:59:59"
                ],
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination')
            ])
        );

        $treatmentScheduleNotes = $this->treatmentScheduleNotesService->search(
            new TreatmentScheduleNoteSearchRequest([
                'filters' => [
                    'employee_id' => $employee->id,
                    'date_from' => "{$date} 00:00:00",
                    'date_to' => "{$date} 23:59:59"
                ],
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination')
            ])
        );

        $records = $this->search($searchRequest);
        $schedules = collect(DatesHelper::dateTreatmentSchedules($date))->all();
        $date = Carbon::parse($date);
        $pdf = Pdf::loadView('pdf.treatment_reservations_employee_schedules', [
            'data' => [
                'records' => $records->getData()->sortBy(fn ($item) => strtotime($item->time)),
                'schedules' => $schedules,
                'employee' => $employee,
                'employeesTimeOff' => $employeesTimeOff->getData(),
                'treatmentScheduleNotes' => $treatmentScheduleNotes->getData(),
                'day' => DatesHelper::spanishWeekDay($date->weekday()),
                'date' => DatesHelper::spanishWeekDay(
                    $date->weekday()
                ) . ', ' . $date->day . ' de ' . DatesHelper::spanishMonthName($date->month) . ' del ' . $date->year
            ]
        ])->setPaper('a4');

        return (new TreatmentReservationSchedulesPdfResponse(Response::STATUSES['OK']))
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Reservas - ' . $date,
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param array $data
     * @return TreatmentReservationSendUpcomingReservationEmailResponse
     * @throws InvalidStatusException
     */
    public function sendEmail(array $data): TreatmentReservationSendUpcomingReservationEmailResponse
    {
        $response = app(TreatmentReservationSendUpcomingReservationEmailResponse::class, ['status' => 'Ok']);

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
