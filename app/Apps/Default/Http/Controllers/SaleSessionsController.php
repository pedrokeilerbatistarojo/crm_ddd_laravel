<?php

namespace Apps\Default\Http\Controllers;

use Domain\SaleSessions\Contracts\Services\SaleSessionsService;
use Domain\SaleSessions\DataTransferObjects\SaleSessionSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SaleSessionsController extends Controller
{
    /**
     * @var SaleSessionsService
     */
    protected SaleSessionsService $saleSessionsService;

    /**
     * @param SaleSessionsService $saleSessionsService
     */
    public function __construct(SaleSessionsService $saleSessionsService)
    {
        $this->saleSessionsService = $saleSessionsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->saleSessionsService->search(
            new SaleSessionSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => $request->get('paginate_size', 10)
            ])
        );

        return $this->apiOkResponse(['records' => $result->getData(), 'meta' => $result->getMeta()]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if ($result = $this->saleSessionsService->find($id, explode(',', $request->get('includes')))) {
            return $this->apiOkResponse($result->toArray());
        }

        return $this->apiRecordNotFoundResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function activeSession(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse(
                $this->saleSessionsService->activeSession(explode(',', $request->get('includes')))
            )
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function close(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse(
                $this->saleSessionsService->close([
                    'closed_by' => Auth::id() ?? null,
                    ...$request->all()
                ])
            )
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function reopen(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse(
                $this->saleSessionsService->reopen(explode(',', $request->get('includes')))
            )
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse(
                $this->saleSessionsService->create(
                    $request->merge([
                        'employee_id' => Auth::id(),
                        'start_date' => Carbon::now()->toDateTimeString()
                    ])->all()
                )
            )
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse($this->saleSessionsService->delete($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse($this->saleSessionsService->update($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ordersPdf(Request $request): JsonResponse
    {
        $pdf = $this->saleSessionsService->ordersPdf($request->get('id'));

        if ($pdf->isSuccess()) {
            return $this->apiOkResponse($pdf->getData());
        }

        return $this->apiErrorResponse([], ['PDF can\'t be generated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function paymentsPdf(Request $request): JsonResponse
    {
        $pdf = $this->saleSessionsService->paymentsPdf($request->get('id'));

        if ($pdf->isSuccess()) {
            return $this->apiOkResponse($pdf->getData());
        }

        return $this->apiErrorResponse([], ['PDF can\'t be generated']);
    }
}
