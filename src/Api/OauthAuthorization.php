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
     * @param string $type
     * @param string $domain
     * @throws ApiTypeException
     */
    public function __construct($accessToken, $refreshToken, $type, $domain = '')
    {
        $this->setType($type);
        $this->setDomain($domain);
        $this->setAccessToken($accessToken);
        $this->setRefreshToken($refreshToken);
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
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $accessToken
     * @throws ApiTypeException
     */
    private function setAccessToken($accessToken)
    {
        if (0 === strlen(trim($accessToken))) {
            throw ApiTypeException::createForInvalidAccessToken();
        }

        $this->accessToken = $accessToken;
    }

    /**
     * @param string $refreshToken
     * @throws ApiTypeException
     */
    private function setRefreshToken($refreshToken)
    {
        if (0 === strlen(trim($refreshToken))) {
            throw ApiTypeException::createForInvalidRefreshToken();
        }

        $this->refreshToken = $refreshToken;
    }
}
