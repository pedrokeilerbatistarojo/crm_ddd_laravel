<?php

namespace Apps\Default\Http\Controllers;

use Domain\Localities\Contracts\Services\LocalitiesService;
use Domain\Localities\DataTransferObjects\LocalitySearchRequest;
use Domain\Localities\DataTransferObjects\ProvinceSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LocalitiesController extends Controller
{
    /**
     * @var LocalitiesService
     */
    protected LocalitiesService $LocalitiesService;

    /**
     * @param LocalitiesService $LocalitiesService
     */
    public function __construct(LocalitiesService $LocalitiesService)
    {
        $this->LocalitiesService = $LocalitiesService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->LocalitiesService->createLocality(
                $request->except(['includes']),
                explode(',', $request->get('includes', ''))
            ))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function provinces(Request $request): JsonResponse
    {
        $result = $this->LocalitiesService->provinces(
            new ProvinceSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => config('system.infinite_pagination')
            ])
        );

        return $this->apiOkResponse(['records' => $result->getData(), 'meta' => $result->getMeta()]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->LocalitiesService->search(
            new LocalitySearchRequest([
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
        if ($result = $this->LocalitiesService->find($id, explode(',', $request->get('includes')))) {
            return $this->apiOkResponse($result->toArray());
        }

        return $this->apiRecordNotFoundResponse();
    }
}
