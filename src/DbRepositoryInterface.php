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
     * @param int $shopProductId
     * @param int $shopVariantId
     * @return ProductMapping
     */
    public function getProductMappingByVariantId($grShopId, $shopProductId, $shopVariantId);

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
     * @param string $shopId
     * @param int $productId
     * @param int $variantId
     * @param string $grProductId
     * @param string $grVariantId
     */
    public function saveProductMapping(ProductMapping $shopId, $productId, $variantId, $grProductId, $grVariantId);


    public function getCustomFieldMapping();

}
