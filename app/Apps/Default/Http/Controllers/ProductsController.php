<?php

namespace Apps\Default\Http\Controllers;

use Domain\Products\Contracts\Services\ProductsService;
use Domain\Products\DataTransferObjects\ProductSearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Support\Core\Enums\SQLSort;

class ProductsController extends Controller
{
    /**
     * @var ProductsService
     */
    protected ProductsService $productsService;

    /**
     * @param ProductsService $productsService
     */
    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->productsService->search(
            new ProductSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => explode(',', $request->get('includes', '')),
                'paginateSize' => $request->get('paginate_size', 10),
                'sortField' => $request->get('sort_field', 'id'),
                'sortType' => SQLSort::from($request->get('sort_type', 'DESC')),
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
        if ($result = $this->productsService->find($id, explode(',', $request->get('includes')))) {
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
            fn() => $this->apiOkResponse($this->productsService->create(
                $request->except(['includes']),
                explode(',', $request->get('includes', ''))
            ))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse($this->productsService->delete(
                $request->except(['includes']),
                explode(',', $request->get('includes', ''))
            ))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn() => $this->apiOkResponse($this->productsService->update(
                $request->except(['includes']),
                explode(',', $request->get('includes', ''))
            ))
        );
    }
}
