<?php

namespace Support;

class Constants
{
    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    /**
     * Response Code
     */
    public const RESPONSE_CODE_AUTHENTICATION_EXCEPTION = 401;

    public const RESPONSE_CODE_AUTHORIZATION_EXCEPTION = 403;

    public const RESPONSE_CODE_ERROR = 500;

    public const RESPONSE_CODE_METHOD_NOT_ALLOWED_EXCEPTION = 405;

    public const RESPONSE_CODE_NOT_FOUND_HTTP_EXCEPTION = 404;

    public const RESPONSE_CODE_SUCCESS = 200;

    public const RESPONSE_CODE_UNPROCESSABLE_ENTITY_HTTP_EXCEPTION = 422;

    public const RESPONSE_CODE_VALIDATION_ERROR = 422;

    /**
     * Response HTTP Code
     */
    public const RESPONSE_HTTP_CODE_AUTHENTICATION_EXCEPTION = 401;

    public const RESPONSE_HTTP_CODE_AUTHORIZATION_EXCEPTION = 403;

    public const RESPONSE_HTTP_CODE_ERROR = 500;

    public const RESPONSE_HTTP_CODE_METHOD_NOT_ALLOWED_EXCEPTION = 405;

    public const RESPONSE_HTTP_CODE_MODEL_NOT_FOUND_EXCEPTION = 404;

    public const RESPONSE_HTTP_CODE_NOT_FOUND_HTTP_EXCEPTION = 404;

    public const RESPONSE_HTTP_CODE_SUCCESS = 200;

    public const RESPONSE_HTTP_CODE_VALIDATION_ERROR = 422;

    /**
     * Response Messages
     */
    public const RESPONSE_MESSAGE_AUTHENTICATION_EXCEPTION = 'Unauthenticated.';

    public const RESPONSE_MESSAGE_AUTHORIZATION_EXCEPTION = 'Unauthorized to access resource.';

    public const RESPONSE_MESSAGE_ERROR = 'Whoops, looks like something went wrong, if the error continues contact technical support.';

    public const RESPONSE_MESSAGE_METHOD_NOT_ALLOWED_EXCEPTION = 'The request method is not allowed.';

    public const RESPONSE_MESSAGE_MODEL_NOT_FOUND_EXCEPTION = 'The resource you are looking for does not exist.';

    public const RESPONSE_MESSAGE_NOT_FOUND_HTTP_EXCEPTION = 'The uri requested does not exist.';

    public const RESPONSE_MESSAGE_SUCCESS = '';

    public const RESPONSE_MESSAGE_UNPROCESSABLE_ENTITY_HTTP_EXCEPTION = 'Whoops, looks like your data has some validation errors.';

    public const RESPONSE_MESSAGE_VALIDATION_EXCEPTION = 'Whoops, looks like your data has some validation errors.';

    public const RESPONSE_STATUS_ERROR = 'ERROR';

    /**
     * Response Status
     */
    public const RESPONSE_STATUS_SUCCESS = 'OK';
}
