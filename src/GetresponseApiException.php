<?php
namespace GrShareCode;

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
}
