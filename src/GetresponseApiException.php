<?php
namespace GrShareCode;

use Exception;

/**
 * Class GetresponseApiException
 * @package GrShareCode
 */
class GetresponseApiException extends GrShareCodeException
{
    /**
     * @param string $errorMessage
     * @return GetresponseApiException
     */
    public static function createForInvalidCurlResponse($errorMessage)
    {
        return new self($errorMessage, self::INVALID_CURL_RESPONSE);
    }

    /**
     * @param string $message
     * @param int $httpStatus
     * @return GetresponseApiException
     */
    public static function createForInvalidApiResponseCode($message, $httpStatus)
    {
        return new self($message, $httpStatus);
    }

    /**
     * @return GetresponseApiException
     */
    public static function createForInvalidAuthentication()
    {
        return new self('Invalid Authentication params', self::INVALID_AUTHENTICATION);
    }

    /**
     * @param Exception $e
     * @return GetresponseApiException
     */
    public static function createFromPreviousException(Exception $e)
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
