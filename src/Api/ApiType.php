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

    /** @var string */
    private $domain;

    /** @var array */
    private $apiTypes = [self::SMB, self::MX_PL, self::MX_US];

    /**
     * @param string $type
     * @param null $domain
     * @throws ApiTypeException
     */
    public function __construct($type, $domain = null)
    {
        $this->validateApiType($type);
        $this->validateApiDomain($type, $domain);
        $this->type = $type;
        $this->domain = $domain;
    }

    /**
     * @param string $apiType
     * @throws ApiTypeException
     */
    private function validateApiType($apiType)
    {
        if (!in_array($apiType, $this->apiTypes, true)) {
            throw ApiTypeException::createForInvalidApiType();
        }
    }

    /**
     * @param string $type
     * @param string $domain
     * @throws ApiTypeException
     */
    private function validateApiDomain($type, $domain)
    {
        if (empty($domain) && in_array($type, [self::MX_US, self::MX_PL], true)) {
            throw ApiTypeException::createForInvalidApiType();
        }
    }

    /**
     * @return ApiType
     * @throws ApiTypeException
     */
    public static function createForSMB()
    {
        return new self(self::SMB);
    }

    /**
     * @param string $domain
     * @return ApiType
     * @throws ApiTypeException
     */
    public static function createForMxUs($domain)
    {
        return new self(self::MX_US, $domain);
    }

    /**
     * @param string $domain
     * @return ApiType
     * @throws ApiTypeException
     */
    public static function createForMxPl($domain)
    {
        return new self(self::MX_PL, $domain);
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
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
     * @return bool
     */
    public function isMx()
    {
        return in_array($this->type, [self::MX_US, self::MX_PL], true);
    }
}
