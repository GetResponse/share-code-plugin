<?php

namespace GrShareCode\Api;

/**
 * Class OauthAuthorizationMethod
 * @package GrShareCode\Api
 */
class OauthAuthorization extends Authorization implements AuthorizationInterface
{
    /** @var string */
    private $accessToken;

    /** @var string */
    private $refreshToken;

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param string $domain
     * @param string $type
     * @throws ApiTypeException
     */
    public function __construct($accessToken, $refreshToken, $domain, $type)
    {
        $this->validateApiType($type);
        $this->validateApiDomain($type, $domain);

        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->domain = $domain;
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function getAuthorizationHeader()
    {
        return 'Authorization: Bearer ' . $this->accessToken;
    }

    /**
     * @return string
     */
    public function getAuthorizationKey()
    {
        return $this->accessToken;
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
