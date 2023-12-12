<?php

namespace Apps\Default\Http\Controllers;

use Illuminate\Http\Request;
use Support\Core\Enums\SQLSort;
use Illuminate\Http\JsonResponse;
use Domain\Clients\Contracts\Services\ClientsService;
use Domain\Clients\DataTransferObjects\ClientSearchRequest;
use Domain\Clients\Models\Client;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ClientsController extends Controller
{
    /**
     * @var ClientsService
     */
    protected ClientsService $clientsService;

    /**
     * @param ClientsService $clientsService
     */
    public function __construct(ClientsService $clientsService)
    {
        $this->clientsService = $clientsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function search(Request $request): JsonResponse
    {
        $result = $this->clientsService->search(
            new ClientSearchRequest([
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
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function exportExcel(Request $request): JsonResponse
    {
        $result = $this->clientsService->exportExcel(
            new ClientSearchRequest([
                'filters' => $request->except(['includes', 'paginate_size']),
                'includes' => ['locality', 'locality.province'],
                'sortField' => $request->get('sort_field', 'id'),
                'sortType' => SQLSort::from($request->get('sort_type', 'DESC')),
            ]),
        );

        $path = storage_path('app/'.$result);
        $content = file_get_contents($path);
        @unlink($path);

        return $this->apiOkResponse(['title' => $result, 'content' => base64_encode($content)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function duplicates(Request $request): JsonResponse
    {
        $result = $this->clientsService->duplicates(
            new ClientSearchRequest([
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
        if ($result = $this->clientsService->find($id, explode(',', $request->get('includes')))) {
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
            fn () => $this->apiOkResponse($this->clientsService->create($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->clientsService->delete($request->all()))
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return $this->executeAction(
            fn () => $this->apiOkResponse($this->clientsService->update($request->all()))
        );
    }

    /**
     * @param Client $client
     * @return JsonResponse
     */
    public function downloadTemplate(Client $client): JsonResponse
    {
        $pdf = $this->clientsService->downloadTemplate($client);

        if ($pdf->isSuccess()) {
            return $this->apiOkResponse($pdf->getData());
        }

        return $this->apiErrorResponse([], ['PDF can\'t be generated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendConsentEmail(Request $request): JsonResponse
    {
        $response = $this->clientsService->sendConsentEmail($request->get('id'), $request->get('email'));

        if ($response->isSuccess()) {
            return $this->apiOkResponse([], ['Email was sent successfully']);
        }

        return $this->apiErrorResponse($response->getErrors(), ['Email can\'t be send']);
    }
}
