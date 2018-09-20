<?php
namespace GrShareCode\Api;

/**
 * Interface AuthorizationMethod
 * @package GrShareCode\Api
 */
abstract class Authorization
{
    const API_URL_MX_US = 'https://api3.getresponse360.com/v3/';
    const API_URL_MX_PL = 'https://api3.getresponse360.pl/v3/';
    const API_URL_SMB = 'https://api.getresponse.com/v3/';

    const SMB = 'smb';
    const MX_US = 'mx_us';
    const MX_PL = 'mx_pl';

    const API_TYPES = [self::SMB, self::MX_PL, self::MX_US];
    const SMB_API_DOMAIN = 'https://app.getresponse.com';

    const SMB_TOKEN_URL = 'https://api.getresponse.com/v3/token';
    const MX_US_TOKEN_URL = 'https://api3.getresponse360.com/v3/token';
    const MX_PL_TOKEN_URL = 'https://api3.getresponse360.pl/v3/token';

    /** @var string */
    protected $domain;

    /** @var string */
    protected $type;

    /**
     * @param string $type
     * @throws ApiTypeException
     */
    protected function setType($type)
    {
        if (!in_array($type, self::API_TYPES, true)) {
            throw ApiTypeException::createForInvalidApiType();
        }

        $this->type = $type;
    }

    /**
     * @param string $domain
     * @throws ApiTypeException
     */
    protected function setDomain($domain)
    {
        if (empty($domain) && in_array($this->type, [self::MX_US, self::MX_PL], true)) {
            throw ApiTypeException::createForInvalidApiType();
        }

        $this->domain = $domain;
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

    /**
     * @param string $type
     * @param string $domain
     * @param string $clientId
     * @param string $shop
     * @return string
     */
    public static function getAuthorizationUrl($type, $domain, $clientId, $shop)
    {
        if ($type === self::SMB) {
            $domain = self::SMB_API_DOMAIN;
        } else {
            $domain = 'https://' . $domain;
        }

        $url = $domain . '/oauth2_authorize.html?' . http_build_query([
                'response_type' => 'code',
                'client_id' => $clientId,
                'state' => $shop
            ]);

        return $url;
    }

    /**
     * @param string $type
     * @return string
     */
    public static function getTokenUrl($type)
    {
        switch ($type) {
            case self::SMB:
                return self::SMB_TOKEN_URL;
                break;
            case self::MX_US:
                return self::MX_US_TOKEN_URL;
                break;
            default:
                return self::MX_PL_TOKEN_URL;
        }
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
    public function getType()
    {
        return $this->type;
    }
}
