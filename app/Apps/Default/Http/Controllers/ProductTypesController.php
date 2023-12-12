<?php

namespace Apps\Default\Http\Controllers;

use Illuminate\Http\Request;
use Support\Core\Enums\SQLSort;
use Illuminate\Http\JsonResponse;
use Domain\Products\Contracts\Services\ProductsService;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Domain\Products\DataTransferObjects\ProductTypeSearchRequest;

class ProductTypesController extends Controller
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
        $result = $this->productsService->searchProductTypes(
            new ProductTypeSearchRequest([
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
        if ($result = $this->productsService->findProductType($id, explode(',', $request->get('includes')))) {
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
            fn() => $this->apiOkResponse($this->productsService->createProductType(
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
            fn() => $this->apiOkResponse($this->productsService->deleteProductType(
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
            fn() => $this->apiOkResponse($this->productsService->updateProductType(
                $request->except(['includes']),
                explode(',', $request->get('includes', ''))
            ))
        );
    }
}
