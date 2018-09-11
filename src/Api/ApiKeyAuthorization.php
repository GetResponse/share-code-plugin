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
     * @param string $domain
     * @param string $type
     * @throws ApiTypeException
     */
    public function __construct($apiKey, $domain, $type)
    {
        $this->validateApiType($type);
        $this->validateApiDomain($type, $domain);

        $this->apiKey = $apiKey;
        $this->domain = $domain;
        $this->type = $type;
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
}
