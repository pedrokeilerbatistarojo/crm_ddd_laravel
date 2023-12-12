<?php

namespace Apps\Default\Http\Controllers;

use Closure;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Support\Constants;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param       $data
     * @param array $messages
     * @param int $responseCode
     * @param int $httpCode
     *
     * @return JsonResponse
     */
    public function apiErrorResponse(
        $data,
        array $messages = [Constants::RESPONSE_MESSAGE_ERROR],
        int $responseCode = Constants::RESPONSE_CODE_ERROR,
        int $httpCode = Constants::RESPONSE_HTTP_CODE_ERROR
    ): JsonResponse {
        return $this->apiFormattedResponse(
            Constants::RESPONSE_STATUS_ERROR,
            $responseCode,
            $messages,
            $data,
            $httpCode
        );
    }

    /**
     * @param       $status
     * @param       $responseCode
     * @param array $messages
     * @param       $data
     * @param       $httpCode
     *
     * @return JsonResponse
     */
    public function apiFormattedResponse($status, $responseCode, array $messages, $data, $httpCode): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $responseCode,
            'messages' => $messages,
            'data' => $data,
        ], $httpCode);
    }

    /**
     * @param       $data
     * @param array $messages
     * @param int $responseCode
     * @param int $httpCode
     *
     * @return JsonResponse
     */
    public function apiOkResponse(
        $data,
        array $messages = [Constants::RESPONSE_MESSAGE_SUCCESS],
        int $responseCode = Constants::RESPONSE_CODE_SUCCESS,
        int $httpCode = Constants::RESPONSE_HTTP_CODE_SUCCESS
    ): JsonResponse {
        return $this->apiFormattedResponse(
            Constants::RESPONSE_STATUS_SUCCESS,
            $responseCode,
            $messages,
            $data,
            $httpCode
        );
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function apiRecordNotFoundResponse(array $data = []): JsonResponse
    {
        return $this->apiErrorResponse(
            $data,
            [Constants::RESPONSE_MESSAGE_MODEL_NOT_FOUND_EXCEPTION],
            Constants::RESPONSE_CODE_NOT_FOUND_HTTP_EXCEPTION,
            Constants::RESPONSE_HTTP_CODE_NOT_FOUND_HTTP_EXCEPTION
        );
    }

    public function apiValidationErrorResponse(
        $data,
        array $messages = [Constants::RESPONSE_MESSAGE_VALIDATION_EXCEPTION],
        int $responseCode = Constants::RESPONSE_CODE_VALIDATION_ERROR,
        int $httpCode = Constants::RESPONSE_HTTP_CODE_VALIDATION_ERROR
    ): JsonResponse {
        return $this->apiFormattedResponse(
            Constants::RESPONSE_STATUS_ERROR,
            $responseCode,
            $messages,
            $data,
            $httpCode
        );
    }

    /**
     * @param Closure $action
     * @return JsonResponse
     */
    public function executeAction(Closure $action): JsonResponse
    {
        try {
            return $action();
        } catch (ValidationException $exception) {
            return $this->apiValidationErrorResponse($exception->errors());
        } catch (Exception $exception) {
            logger($exception);
            return $this->apiErrorResponse($exception->getMessage());
        }
    }
}
