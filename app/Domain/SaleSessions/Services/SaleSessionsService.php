<?php

namespace Domain\SaleSessions\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Domain\Orders\Contracts\Services\OrdersService;
use Domain\Orders\DataTransferObjects\OrderSearchRequest;
use Domain\Orders\Enums\OrderType;
use Domain\SaleSessions\Actions\CloseSaleSession;
use Domain\SaleSessions\Actions\CreateSaleSession;
use Domain\SaleSessions\Actions\DeleteSaleSession;
use Domain\SaleSessions\Actions\ReopenSaleSession;
use Domain\SaleSessions\Actions\UpdateSaleSession;
use Domain\SaleSessions\Contracts\Repositories\SaleSessionsRepository;
use Domain\SaleSessions\DataTransferObjects\SaleSessionEntitiesCollection;
use Domain\SaleSessions\DataTransferObjects\SaleSessionEntity;
use Domain\SaleSessions\DataTransferObjects\SaleSessionPDFResponse;
use Domain\SaleSessions\DataTransferObjects\SaleSessionSearchRequest;
use Domain\SaleSessions\DataTransferObjects\SaleSessionSearchResponse;
use Domain\SaleSessions\Models\SaleSession;
use Domain\SaleSessions\Transformers\SaleSessionTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\DataTransferObjects\Contracts\Response;
use Support\Exceptions\InvalidDataTypeException;

class SaleSessionsService implements \Domain\SaleSessions\Contracts\Services\SaleSessionsService
{
    /**
     * @param SaleSessionsRepository $repository
     * @param OrdersService $ordersService
     */
    public function __construct(
        protected SaleSessionsRepository $repository,
        protected OrdersService $ordersService
    ) {
    }

    /**
     * @param array $includes
     * @return SaleSessionEntity|null
     * @throws UnknownProperties
     */
    public function activeSession(array $includes = []): ?SaleSessionEntity
    {
        $record = $this->repository->activeSession();

        return $record ? $this->DTOFromModel($record, $includes) : null;
    }

    /**
     * @param array $data
     * @return SaleSessionEntity
     * @throws UnknownProperties
     */
    public function close(array $data): SaleSessionEntity
    {
        $record = app(CloseSaleSession::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return SaleSessionEntity
     * @throws UnknownProperties
     */
    public function create(array $data): SaleSessionEntity
    {
        $record = app(CreateSaleSession::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param array $data
     * @return SaleSessionEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): SaleSessionEntity
    {
        $record = app(DeleteSaleSession::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?SaleSessionEntity
    {
        if (!$record = $this->repository->find($id)) {
            return null;
        }

        return $this->DTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @return SaleSessionPDFResponse
     */
    public function ordersPdf(int $id): SaleSessionPDFResponse
    {
        $record = $this->repository->find($id);

        $ordersSearchRequest = app(OrderSearchRequest::class, [
            'args' => [
                'filters' => ['sale_session_id' => $id],
                'includes' => ['payments', 'company'],
                'paginateSize' => config('system.infinite_pagination')
            ]
        ]);

        $orders = $this->ordersService->search($ordersSearchRequest)->getData();
        $records = [];
        foreach ($orders as $order) {
            $records[$order->company->name][] = $order;
        }
        ksort($records);

        $pdf = Pdf::loadView('pdf.sale_session_orders', [
            'data' => [
                'record' => $record,
                'companies' => $records,
            ]
        ]);

        return app(SaleSessionPDFResponse::class, ['status' => Response::STATUSES['OK']])
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Cierra Caja #' . $id . ' - Compras',
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param int $id
     * @return SaleSessionPDFResponse
     */
    public function paymentsPdf(int $id): SaleSessionPDFResponse
    {
        $record = $this->repository->find($id);

        $ordersSearchRequest = app(OrderSearchRequest::class, [
            'args' => [
                'filters' => ['sale_session_id' => $id],
                'includes' => ['payments', 'client', 'company'],
                'paginateSize' => config('system.infinite_pagination')
            ]
        ]);

        $orders = $this->ordersService->search($ordersSearchRequest);

        $payments = [];

        foreach ($orders->getData() as $order) {
            if ($order->payments) {
                foreach ($order->payments as $payment) {
                    $client = '';
                    if ($order->type === OrderType::CLIENT->value && $order->client) {
                        $client = $order->client->name;
                    } elseif ($order->type === OrderType::TELEPHONE_SALE->value) {
                        $client = $order->telephone_sale_seq;
                    } elseif ($order->type === OrderType::COUNTER_SALE->value) {
                        $client = $order->counter_sale_seq;
                    }

                    $payments[$order->company->name][$payment['type']][] = [
                        'ticket_number' => $order->ticket_number,
                        'type' => $order->type,
                        'client' => $client,
                        'paid_date' => Carbon::parse($payment['paid_date'])->format('d/m/Y'),
                        'amount' => $payment['amount'],
                    ];
                }
            }
        }

        ksort($payments);

        $pdf = Pdf::loadView('pdf.sale_session_payments', [
            'data' => [
                'record' => $record,
                'payments' => $payments,
            ]
        ]);

        return app(SaleSessionPDFResponse::class, ['status' => Response::STATUSES['OK']])
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Cierra Caja #' . $id . ' - Pagos',
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param array $includes
     * @return SaleSessionEntity|null
     * @throws UnknownProperties
     */
    public function reopen(array $includes = []): ?SaleSessionEntity
    {
        $record = app(ReopenSaleSession::class)();

        return $record ? $this->DTOFromModel($record, $includes) : null;
    }

    /**
     * @param SaleSessionSearchRequest $request
     * @return SaleSessionSearchResponse
     * @throws UnknownProperties
     * @throws InvalidDataTypeException
     */
    public function search(SaleSessionSearchRequest $request): SaleSessionSearchResponse
    {
        $query = $this->repository->searchQueryBuilder($request->filters, $request->sortField, $request->sortType);
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(SaleSessionTransformer::class));
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new SaleSessionSearchResponse('Ok'))->setData(
            SaleSessionEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param array $data
     * @return SaleSessionEntity
     * @throws UnknownProperties
     */
    public function update(array $data): SaleSessionEntity
    {
        $record = app(UpdateSaleSession::class)($data);

        return $this->DTOFromModel($record);
    }

    /**
     * @param SaleSession $entity
     * @param array $includes
     * @return SaleSessionEntity
     * @throws UnknownProperties
     */
    private function DTOFromModel(SaleSession $entity, array $includes = []): SaleSessionEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(SaleSessionTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new SaleSessionEntity($data);
    }
}
