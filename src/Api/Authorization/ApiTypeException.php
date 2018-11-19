<?php
namespace GrShareCode\Api\Authorization;

use GrShareCode\Api\Exception\GetresponseApiException;


/**
 * Class ApiTypeException
 * @package GrShareCode\Api\Authorization
 */
class ApiTypeException extends GetresponseApiException
{
    /**
     * @return ApiTypeException
     */
    public static function createForInvalidApiType()
    {
        return new self('Invalid API type', self::INVALID_API_TYPE);
    }

    /**
     * @return ApiTypeException
     */
    public static function createForInvalidApiDomain()
    {
        return new self('Invalid API domain', self::INVALID_API_DOMAIN);
    }

    /**
     * @return ApiTypeException
     */
    public static function createForInvalidAccessToken()
    {
        return new self('Invalid Access Token', self::INVALID_ACCESS_TOKEN);
    }

    /**
     * @return ApiTypeException
     */
    public static function createForInvalidRefreshToken()
    {
        return new self('Invalid Refresh Token', self::INVALID_REFRESH_TOKEN);
    }

    /**
     * @return ApiTypeException
     */
    public static function createForInvalidApiKey()
    {
        return new self('Invalid API Key', self::INVALID_API_KEY);
    }
}
