<?php
namespace GrShareCode\Api;

/**
 * Class ApiType
 * @package GrShareCode\Api
 */
class ApiType
{
    const API_URL_MX_US = 'https://api3.getresponse360.com/v3/';
    const API_URL_MX_PL = 'https://api3.getresponse360.pl/v3/';
    const API_URL_SMB = 'https://api.getresponse.com/v3/';

    const SMB = 'smb';
    const MX_US = 'mx_us';
    const MX_PL = 'mx_pl';

    /** @var string */
    private $type;

    private $apiTypes = [self::SMB, self::MX_PL, self::MX_US];

    /**
     * @return ApiType
     * @throws ApiTypeException
     */
    public static function createForSMB()
    {
        return new self(self::SMB);
    }

    /**
     * @return ApiType
     * @throws ApiTypeException
     */
    public static function createForMxUs()
    {
        return new self(self::MX_US);
    }

    /**
     * @return ApiType
     * @throws ApiTypeException
     */
    public static function createForMxPl()
    {
        return new self(self::MX_PL);
    }

    /**
     * @param string $type
     * @throws ApiTypeException
     */
    public function __construct($type)
    {
        $this->validateApiType($type);
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        switch ($this->type) {
            case self::MX_PL:
                $url = self::API_URL_MX_PL;
                break;

            case self::MX_US:
                $url = self::API_URL_MX_US;
                break;

            default:
                $url = self::API_URL_SMB;
        }

        return $url;
    }

    /**
     * @param $apiType
     * @throws ApiTypeException
     */
    private function validateApiType($apiType)
    {
        if (!in_array($apiType, $this->apiTypes)) {
            throw ApiTypeException::createForInvalidApiType();
        }
    }
}
