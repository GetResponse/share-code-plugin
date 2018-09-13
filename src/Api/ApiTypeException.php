<?php
namespace GrShareCode\Api;

use GrShareCode\GrShareCodeException;

/**
 * Class ApiTypeException
 * @package GrShareCode\Api
 */
class ApiTypeException extends GrShareCodeException
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
