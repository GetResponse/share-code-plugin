<?php
namespace GrShareCode;

use GrShareCode\Cart\ContactNotFoundException;
use GrShareCode\Product\Product;

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
     * @param string $listId
     * @return array
     * @throws ContactNotFoundException
     */
    public function getContactByEmail($email, $listId)
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
