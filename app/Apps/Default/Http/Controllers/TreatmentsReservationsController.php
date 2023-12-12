<?php

namespace Apps\Default\Http\Controllers;

use Domain\Employees\Models\Employee;
use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class TreatmentsReservationsController extends Controller
{
    /**
     * @var TreatmentReservationsService
     */
    private TreatmentReservationsService $treatmentReservationsService;

    /**
     * @param TreatmentReservationsService $treatmentReservationsService
     */
    public function __construct(TreatmentReservationsService $treatmentReservationsService)
    {
        $this->treatmentReservationsService = $treatmentReservationsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentReservationsService->create($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function markAsUsed(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentReservationsService->markAsUsed($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentReservationsService->update($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentReservationsService->delete($request->all()))
        );
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        if ($result = $this->treatmentReservationsService->find($id, explode(',', $request->get('includes', '')))) {
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
        $result = $this->treatmentReservationsService->search(
            new TreatmentReservationSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => $request->get('paginate_size', 10)
            ])
        );

        return $this->apiOkResponse(['records' => $result->getData(), 'meta' => $result->getMeta()]);
    }

    /**
     * @param string $date
     * @param Request $request
     * @return JsonResponse
     */
    public function schedulesPdf(string $date, Request $request): JsonResponse
    {
        $pdf = $this->treatmentReservationsService->schedulesPdf($date, $request);

        if ($pdf->isSuccess()) {
            return $this->apiOkResponse($pdf->getData());
        }

        return $this->apiErrorResponse([], ['PDF can\'t be generated']);
    }

    /**
     * @param string $date
     * @param Employee $employee
     * @param Request $request
     * @return JsonResponse
     */
    public function schedulesEmployeePdf(string $date, Employee $employee, Request $request): JsonResponse
    {
        $pdf = $this->treatmentReservationsService->schedulesEmployeePdf($date, $employee, $request);

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
        $result = $this->treatmentReservationsService->search(
            new TreatmentReservationSearchRequest([
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
            fn () => $this->apiOkResponse($this->treatmentReservationsService->sendEmail($request->all()))
        );
    }
}
