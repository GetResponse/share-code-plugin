<?php
namespace ShareCode;

use ShareCode\Cart\Product;

/**
 * Class GetresponseApi
 * @package ShareCode
 */
class GetresponseApi
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
     * @param string $email
     * @param string $campaignId
     * @return array
     * @throws GetresponseApiException
     */
    public function getSubscriberByEmail($email, $campaignId)
    {
        return [];
    }

    /**
     * @param Product $product
     * @throws GetresponseApiException
     */
    public function createProduct(Product $product)
    {
        $product->setGrVariantId('');
    }

    /**
     * @param array $params
     * @return string
     */
    public function createCart($params)
    {
        return '';
    }

    /**
     * @param array $params
     */
    public function updateCart($params)
    {

    }
}
