<?php

namespace Domain\Orders\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Orders\Actions\EditNoteOrder;
use Domain\Orders\Actions\MarkUsedPurchaseOrder;
use Domain\Clients\Contracts\Services\ClientsService;
use Domain\Orders\Actions\ApproveOrder;
use Domain\Orders\Actions\CreateOrder;
use Domain\Orders\Actions\CreateOrderDetail;
use Domain\Orders\Actions\DeleteOrder;
use Domain\Orders\Actions\DeleteOrderApproval;
use Domain\Orders\Actions\DeleteOrderDetail;
use Domain\Orders\Actions\UpdateOrder;
use Domain\Orders\Actions\UpdateOrderDetail;
use Domain\Orders\Contracts\Repositories\OrderDetailsRepository;
use Domain\Orders\Contracts\Repositories\OrdersApprovalRepository;
use Domain\Orders\Contracts\Repositories\OrdersRepository;
use Domain\Orders\DataTransferObjects\OrderApprovalEntitiesCollection;
use Domain\Orders\DataTransferObjects\OrderApprovalEntity;
use Domain\Orders\DataTransferObjects\OrderApprovalSearchRequest;
use Domain\Orders\DataTransferObjects\OrderApprovalSearchResponse;
use Domain\Orders\DataTransferObjects\OrderDetailEntitiesCollection;
use Domain\Orders\DataTransferObjects\OrderDetailEntity;
use Domain\Orders\DataTransferObjects\OrderDetailSearchRequest;
use Domain\Orders\DataTransferObjects\OrderDetailSearchResponse;
use Domain\Orders\DataTransferObjects\OrderEntitiesCollection;
use Domain\Orders\DataTransferObjects\OrderEntity;
use Domain\Orders\DataTransferObjects\OrderProductionReportPDFResponse;
use Domain\Orders\DataTransferObjects\OrderSearchRequest;
use Domain\Orders\DataTransferObjects\OrderSearchResponse;
use Domain\Orders\DataTransferObjects\OrderSendTicketEmailResponse;
use Domain\Orders\DataTransferObjects\OrderTicketPDFResponse;
use Domain\Orders\Mails\OrderTicket;
use Domain\Orders\Models\Order;
use Domain\Orders\Models\OrderApproval;
use Domain\Orders\Models\OrderDetail;
use Domain\Orders\Transformers\OrderApprovalTransformer;
use Domain\Orders\Transformers\OrderDetailTransformer;
use Domain\Orders\Transformers\OrderTransformer;
use Domain\Products\Contracts\Services\ProductsService;
use Dompdf\Adapter\CPDF;
use Exception;
use Illuminate\Support\Facades\Mail;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Serializers\FractalDataArraySerializer;
use Support\DataTransferObjects\Contracts\Response;
use Support\Exceptions\InvalidDataTypeException;
use Support\Exceptions\InvalidStatusException;

class OrdersService implements \Domain\Orders\Contracts\Services\OrdersService
{
    /**
     * @param OrdersRepository $ordersRepository
     * @param OrdersApprovalRepository $ordersApprovalRepository
     * @param OrderDetailsRepository $orderDetailsRepository
     * @param ClientsService $clientsService
     * @param ProductsService $productsService
     */
    public function __construct(
        protected readonly OrdersRepository $ordersRepository,
        protected readonly OrdersApprovalRepository $ordersApprovalRepository,
        protected readonly OrderDetailsRepository $orderDetailsRepository,
        protected readonly ClientsService $clientsService,
        protected readonly ProductsService $productsService
    ) {
    }

    /**
     * @param array $data
     * @return OrderEntity
     * @throws UnknownProperties
     */
    public function create(array $data): OrderEntity
    {
        $record = app(CreateOrder::class)($data);

        return $this->orderDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return OrderDetailEntity
     * @throws UnknownProperties
     */
    public function createDetail(array $data): OrderDetailEntity
    {
        $record = app(CreateOrderDetail::class)($data);

        return $this->orderDetailDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return OrderEntity
     * @throws UnknownProperties
     */
    public function delete(array $data): OrderEntity
    {
        $record = app(DeleteOrder::class)($data);

        return $this->orderDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return OrderApprovalEntity
     * @throws UnknownProperties
     */
    public function deleteApproval(array $data): OrderApprovalEntity
    {
        $record = app(DeleteOrderApproval::class)($data);

        return $this->orderApprovalDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return OrderDetailEntity
     * @throws UnknownProperties
     */
    public function deleteDetail(array $data): OrderDetailEntity
    {
        $record = app(DeleteOrderDetail::class)($data);

        return $this->orderDetailDTOFromModel($record);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return OrderEntity|null
     * @throws UnknownProperties
     */
    public function find(int $id, array $includes = []): ?OrderEntity
    {
        if (!$record = $this->ordersRepository->find($id)) {
            return null;
        }

        return $this->orderDTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return OrderApprovalEntity|null
     * @throws UnknownProperties
     */
    public function findApproval(int $id, array $includes = []): ?OrderApprovalEntity
    {
        if (!$record = $this->ordersApprovalRepository->find($id)) {
            return null;
        }

        return $this->orderApprovalDTOFromModel($record, $includes);
    }

    /**
     * @param int $id
     * @param array $includes
     * @return OrderDetailEntity|null
     * @throws UnknownProperties
     */
    public function findDetail(int $id, array $includes = []): ?OrderDetailEntity
    {
        if (!$record = $this->orderDetailsRepository->find($id)) {
            return null;
        }

        return $this->orderDetailDTOFromModel($record, $includes);
    }

    /**
     * @param array $ids
     * @return OrderApprovalEntitiesCollection
     */
    public function processApproval(array $ids): OrderApprovalEntitiesCollection
    {
        $orders = app(OrderApprovalEntitiesCollection::class);

        foreach ($ids as $id) {
            $orders->push(app(ApproveOrder::class)(['id' => $id]));
        }

        return $orders;
    }

    /**
     * @param OrderDetailSearchRequest $request
     * @return OrderProductionReportPDFResponse
     * @throws InvalidDataTypeException
     * @throws InvalidStatusException
     * @throws UnknownProperties
     */
    public function productionReport(OrderDetailSearchRequest $request): OrderProductionReportPDFResponse
    {
        $request->includes = ['product', 'product.productType', 'order', 'order.client', 'order.company'];
        $request->paginateSize = config('system.infinite_pagination');

        $records = $this->searchDetails($request)->getData();

        if (array_key_exists('product_type_id', $request->filters) && !empty($request->filters['product_type_id'])) {
            if (is_string($request->filters['product_type_id'])) {
                $productTypeIds = explode(',', $request->filters['product_type_id']);
            } elseif (is_array($request->filters['product_type_id'])) {
                $productTypeIds = $request->filters['product_type_id'];
            } else {
                $productTypeIds = [];
            }
            $records = $records->filter(function ($item) use ($productTypeIds) {
                return in_array($item->product->product_type_id, $productTypeIds);
            });
        }

        $pdf = Pdf::loadView('pdf.production_report', [
            'data' => [
                'records' => $records
            ]
        ])->save(uniqid().'-InformeProducción.pdf');

        return (new OrderProductionReportPDFResponse(Response::STATUSES['OK']))
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Informe Producción',
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param OrderSearchRequest $request
     *
     * @return OrderSearchResponse
     * @throws UnknownProperties|InvalidDataTypeException
     */
    public function search(OrderSearchRequest $request): OrderSearchResponse
    {
        $query = $this->ordersRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(OrderTransformer::class), 'data');

        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new OrderSearchResponse('Ok'))->setData(
            OrderEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param OrderApprovalSearchRequest $request
     * @return OrderApprovalSearchResponse
     * @throws InvalidDataTypeException
     */
    public function searchApprovals(OrderApprovalSearchRequest $request): OrderApprovalSearchResponse
    {
        $query = $this->ordersApprovalRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );

        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(OrderApprovalTransformer::class), 'data');
        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new OrderApprovalSearchResponse('Ok'))->setData(
            OrderApprovalEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param OrderDetailSearchRequest $request
     * @return OrderDetailSearchResponse
     * @throws InvalidDataTypeException
     * @throws UnknownProperties
     */
    public function searchDetails(OrderDetailSearchRequest $request): OrderDetailSearchResponse
    {
        $query = $this->orderDetailsRepository->searchQueryBuilder(
            $request->filters,
            $request->sortField,
            $request->sortType
        );
        $records = $query->paginate($request->paginateSize);
        $collection = new Collection($records->items(), app(OrderDetailTransformer::class), 'data');

        $collection->setPaginator(new IlluminatePaginatorAdapter($records));
        $manager = app(Manager::class);
        $manager->parseIncludes($request->includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $resourceCollection = $manager->createData($collection);

        $result = $resourceCollection->toArray();

        return (new OrderDetailSearchResponse('Ok'))->setData(
            OrderDetailEntitiesCollection::createFromArray($result['data'])
        )->setMeta($result['meta']);
    }

    /**
     * @param int $id
     * @param string $email
     * @return OrderSendTicketEmailResponse
     */
    public function sendTicketEmail(int $id, string $email): OrderSendTicketEmailResponse
    {
        $response = app(OrderSendTicketEmailResponse::class, ['status' => 'Ok']);

        try {
            Mail::to($email)->send(
                new OrderTicket($id, $this)
            );
            $response->setSuccess();
        } catch (Exception $ex) {
            logger($ex);
            $response->setErrors([$ex->getMessage()]);
        }

        return $response;
    }

    /**
     * @param int $id
     * @return OrderTicketPDFResponse
     * @throws UnknownProperties
     */
    public function ticket(int $id): OrderTicketPDFResponse
    {
        $order = $this->orderDTOFromModel(
            $this->ordersRepository->find($id),
            ['orderDetails', 'payments', 'company', 'client']
        );

        $pdf = Pdf::setPaper(array(0,0,225,830))->loadView('pdf.ticket', [
            'data' => [
                'order' => $order
            ]
        ]);

        return app(OrderTicketPDFResponse::class, ['status' => Response::STATUSES['OK']])
            ->setSuccess()
            ->setData(
                [
                    'title' => 'Compra #' . $order->id,
                    'content' => base64_encode($pdf->output())
                ]
            );
    }

    /**
     * @param array $data
     * @return OrderEntity
     * @throws UnknownProperties
     */
    public function update(array $data): OrderEntity
    {
        $record = app(UpdateOrder::class)($data);

        return $this->orderDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return OrderDetailEntity
     * @throws UnknownProperties
     */
    public function updateDetail(array $data): OrderDetailEntity
    {
        $record = app(UpdateOrderDetail::class)($data);

        return $this->orderDetailDTOFromModel($record);
    }

    /**
     * @param OrderApproval $entity
     * @param array $includes
     * @return OrderApprovalEntity
     * @throws UnknownProperties
     */
    protected function orderApprovalDTOFromModel(OrderApproval $entity, array $includes = []): OrderApprovalEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(OrderApprovalTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new OrderApprovalEntity($data);
    }

    /**
     * @param Order $entity
     * @param array $includes
     * @return OrderEntity
     * @throws UnknownProperties
     */
    protected function orderDTOFromModel(Order $entity, array $includes = []): OrderEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(OrderTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new OrderEntity($data);
    }

    /**
     * @param OrderDetail $entity
     * @param array $includes
     * @return OrderDetailEntity
     * @throws UnknownProperties
     */
    protected function orderDetailDTOFromModel(OrderDetail $entity, array $includes = []): OrderDetailEntity
    {
        $manager = app(Manager::class);
        $manager->parseIncludes($includes);
        $manager->setSerializer(new FractalDataArraySerializer());
        $item = new Item($entity, app(OrderDetailTransformer::class));
        $data = $manager->createData($item)->toArray();

        return new OrderDetailEntity($data);
    }

    /**
     * @param array $data
     * @return OrderEntity
     * @throws UnknownProperties
     */
    public function markUsedPurchase(array $data): OrderEntity
    {
        $record = app(MarkUsedPurchaseOrder::class)($data);

        return $this->orderDTOFromModel($record);
    }

    /**
     * @param array $data
     * @return OrderEntity
     * @throws UnknownProperties
     */
    public function editNote(array $data): OrderEntity
    {
        $record = app(EditNoteOrder::class)($data);

        return $this->orderDTOFromModel($record);
    }
}
