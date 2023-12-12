<?php

namespace Apps\Default\Http\Controllers;

use Domain\TreatmentScheduleNotes\Contracts\Services\TreatmentScheduleNotesService;
use Domain\TreatmentScheduleNotes\DataTransferObjects\TreatmentScheduleNoteSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class TreatmentScheduleNotesController extends Controller
{
    /**
     * @var TreatmentScheduleNotesService
     */
    protected TreatmentScheduleNotesService $treatmentScheduleNotesServiceService;

    /**
     * @param TreatmentScheduleNotesService $treatmentScheduleNotesServiceService
     */
    public function __construct(TreatmentScheduleNotesService $treatmentScheduleNotesServiceService)
    {
        $this->treatmentScheduleNotesServiceService = $treatmentScheduleNotesServiceService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->treatmentScheduleNotesServiceService->search(
            new TreatmentScheduleNoteSearchRequest([
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
        if ($result = $this->treatmentScheduleNotesServiceService->find($id, explode(',', $request->get('includes')))) {
            return $this->apiOkResponse($result->toArray());
        }

        return $this->apiRecordNotFoundResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentScheduleNotesServiceService->create($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentScheduleNotesServiceService->delete($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->treatmentScheduleNotesServiceService->update($request->all()))
        );
    }
}
