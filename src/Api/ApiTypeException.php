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
}
