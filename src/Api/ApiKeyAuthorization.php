<?php
namespace GrShareCode\Api;

/**
 * Class ApiKeyAuthorizationMethod
 * @package GrShareCode\Api
 */
class ApiKeyAuthorization extends Authorization implements AuthorizationInterface
{
    /** @var string */
    private $apiKey;

    /**
     * @param string $apiKey
     * @param string $type
     * @param string $domain
     * @throws ApiTypeException
     */
    public function __construct($apiKey, $type, $domain = '')
    {
        $this->setType($type);
        $this->setDomain($domain);
        $this->setApiKey($apiKey);
    }

    /**
     * @return string
     */
    public function getAuthorizationHeader()
    {
        return 'X-Auth-Token: api-key ' . $this->apiKey;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @throws ApiTypeException
     */
    private function setApiKey($apiKey)
    {
        if (0 == strlen(trim($apiKey))) {
            throw ApiTypeException::createForInvalidApiKey();
        }

        $this->apiKey = $apiKey;
    }
}
