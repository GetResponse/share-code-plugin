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
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
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
    public function getAuthorizationKey()
    {
        return $this->apiKey;
    }
}
