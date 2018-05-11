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
    const INVALID_API_KEY = 10003;
}
