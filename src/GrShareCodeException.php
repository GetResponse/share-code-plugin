<?php
namespace GrShareCode;

use Exception;

/**
 * Class GrShareCodeException
 * @package GrShareCode
 */
class GrShareCodeException extends Exception
{
    const INVALID_CURL_RESPONSE = 10001;
    const INVALID_API_TYPE = 10002;
    const INVALID_AUTHENTICATION = 10003;
    const INVALID_API_DOMAIN = 10004;
    const INVALID_CATEGORY = 10005;
    const INVALID_VARIANT = 10006;
    const INVALID_PRODUCT = 10007;
    const INVALID_ADD_CART_COMMAND = 10008;
    const INVALID_ACCESS_TOKEN = 10009;
    const INVALID_REFRESH_TOKEN = 10010;
    const INVALID_API_KEY = 10011;
}
