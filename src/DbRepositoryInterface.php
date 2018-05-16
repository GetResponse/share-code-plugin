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
     * @param string $grShopId
     * @param int $externalCartId
     * @param string $grCartId
     */
    public function saveCartMapping($grShopId, $externalCartId, $grCartId);

    /**
     * @param string $grShopId
     * @param int $externalCartId
     */
    public function getGrCartIdFromMapping($grShopId, $externalCartId);

    /**
     * @param string $grShopId
     * @param int $externalOrderId
     */
    public function getGrOrderIdFromMapping($grShopId, $externalOrderId);

    /**
     * @param string $grShopId
     * @param int $externalOrderId
     * @param string $grOrderId
     */
    public function saveOrderMapping($grShopId, $externalOrderId, $grOrderId);

    /**
     * @param string $grShopId
     * @param int $externalProductId
     * @return ProductMapping
     */
    public function getProductMappingByProductId($grShopId, $externalProductId);

    /**
     * @param ProductMapping $productMapping
     */
    public function saveProductMapping(ProductMapping $productMapping);

}
