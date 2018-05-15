<?php
namespace GrShareCode;

use GrShareCode\Contact\CustomFieldsCollection;

/**
 * Class DbRepositoryInterface
 * @package GrShareCode\Cart
 */
interface DbRepositoryInterface
{
    /**
     * @param string $shopId
     * @param int $variantId
     * @param int $parentId
     */
    public function getProductVariantById($shopId, $variantId, $parentId);

    /**
     * @param string $shopId
     * @param int $productId
     * @param string $grVariantId
     */
    public function saveProductVariant($shopId, $productId, $grVariantId);

    /**
     * @param string $shopId
     * @param int $cartId
     * @param string $grCartId
     */
    public function saveCartMapping($shopId, $cartId, $grCartId);

    /**
     * @param string $shopId
     * @param int $cartId
     */
    public function getGrCartIdFromMapping($shopId, $cartId);

    /**
     * @param string $shopId
     * @param int $orderId
     */
    public function getGrOrderIdFromMapping($shopId, $orderId);

    /**
     * @param string $shopId
     * @param int $orderId
     * @param string $grOrderId
     */
    public function saveOrderMapping($shopId, $orderId, $grOrderId);

    /**
     * @param string $shopId
     * @param int $productId
     */
    public function getProductById($shopId, $productId);

    /**
     * @param string $shopId
     * @param int $productId
     * @param int $variantId
     * @param string $grProductId
     * @param string $grVariantId
     */
    public function saveProductMapping($shopId, $productId, $variantId, $grProductId, $grVariantId);
}
