<?php
namespace GrShareCode;

use GrShareCode\ProductMapping\ProductMapping;

/**
 * Class DbRepositoryInterface
 * @package GrShareCode\Cart
 */
interface DbRepositoryInterface
{
    /**
     * @param string $grShopId
     * @param int $externalProductId
     * @param int $externalVariantId
     * @return ProductMapping
     */
    public function getProductMappingByVariantId($grShopId, $externalProductId, $externalVariantId);

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
     * @param int $shopProductId
     * @return ProductMapping
     */
    public function getProductMappingByProductId($shopId, $shopProductId);

    /**
     * @param ProductMapping $productMapping
     */
    public function saveProductMapping(ProductMapping $productMapping);

}
