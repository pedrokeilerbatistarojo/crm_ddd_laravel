<?php

namespace Apps\Default\Http\Controllers;

use Domain\Orders\Contracts\Services\OrdersService;
use Domain\Orders\DataTransferObjects\OrderDetailSearchRequest;
use Domain\Orders\DataTransferObjects\OrderSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class OrdersController extends Controller
{
    /**
     * @var OrdersService
     */
    protected OrdersService $ordersService;

    /**
     * @param OrdersService $ordersService
     */
    public function __construct(OrdersService $ordersService)
    {
        $this->ordersService = $ordersService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->ordersService->create($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->ordersService->delete($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function productionReport(Request $request): JsonResponse
    {
        $pdf = $this->ordersService->productionReport(
            new OrderDetailSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size'])
            ])
        );

        if ($pdf->isSuccess()) {
            return $this->apiOkResponse($pdf->getData());
        }

        return $this->apiErrorResponse([], ['PDF can\'t be generated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->ordersService->search(
            new OrderSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => $request->get('paginate_size', 10)
            ])
        );

        return $this->apiOkResponse(['records' => $result->getData(), 'meta' => $result->getMeta()]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendTicketEmail(Request $request): JsonResponse
    {
        $response = $this->ordersService->sendTicketEmail($request->get('id'), $request->get('email'));

        if ($response->isSuccess()) {
            return $this->apiOkResponse([], ['Email was sent successfully']);
        }

        return $this->apiErrorResponse($response->getErrors(), ['Email can\'t be send']);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if ($result = $this->ordersService->find($id, explode(',', $request->get('includes')))) {
            return $this->apiOkResponse($result->toArray());
        }

        return $this->apiRecordNotFoundResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ticket(Request $request): JsonResponse
    {
        $pdf = $this->ordersService->ticket($request->get('id'));

        if ($pdf->isSuccess()) {
            return $this->apiOkResponse($pdf->getData());
        }

        return $this->apiErrorResponse([], ['PDF can\'t be generated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->ordersService->update($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function markUsedPurchase(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->ordersService->markUsedPurchase($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function editNote(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->ordersService->editNote($request->all()))
        );
    }
}
