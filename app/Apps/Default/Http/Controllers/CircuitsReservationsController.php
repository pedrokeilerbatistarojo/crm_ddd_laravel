<?php

namespace Apps\Default\Http\Controllers;

use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use function GuzzleHttp\Promise\all;

class CircuitsReservationsController extends Controller
{
    /**
     * @var CircuitReservationsService
     */
    private CircuitReservationsService $circuitReservationsService;

    /**
     * @param CircuitReservationsService $circuitReservationsService
     */
    public function __construct(CircuitReservationsService $circuitReservationsService)
    {
        $this->circuitReservationsService = $circuitReservationsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->circuitReservationsService->create($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function markAsUsed(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->circuitReservationsService->markAsUsed($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->circuitReservationsService->update($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->circuitReservationsService->delete($request->all()))
        );
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if ($result = $this->circuitReservationsService->find($id, explode(',', $request->get('includes', '')))) {
            return $this->apiOkResponse($result->toArray());
        }

        return $this->apiRecordNotFoundResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->circuitReservationsService->search(
            new CircuitReservationSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => $request->get('paginate_size', 10)
            ])
        );

        return $this->apiOkResponse(['records' => $result->getData(), 'meta' => $result->getMeta()]);
    }

    /**
     * @param string $date
     * @return JsonResponse
     */
    public function schedulesPdf(string $date): JsonResponse
    {
        $pdf = $this->circuitReservationsService->schedulesPdf($date);

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
    public function summary(Request $request): JsonResponse
    {
        $result = $this->circuitReservationsService->search(
            new CircuitReservationSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination')
            ])
        );

        $responseData = $result->getData();

        if ($request->has('group_results')) {
            $responseData = $responseData->groupBy(explode(',', $request->get('group_results')));
        }

        return $this->apiOkResponse($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendEmail(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->circuitReservationsService->sendEmail($request->all()))
        );
    }

}
