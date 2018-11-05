<?php
namespace GrShareCode;

use GrShareCode\Contact\ContactCustomField;
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
     * @param int $externalCartId
     * @param string $grCartId
     */
    public function removeCartMapping($grShopId, $externalCartId, $grCartId);

    /**
     * @param string $grShopId
     * @param int $externalOrderId
     */
    public function getGrOrderIdFromMapping($grShopId, $externalOrderId);

    /**
     * @param string $grShopId
     * @param int $externalOrderId
     * @return string
     */
    public function getPayloadMd5FromOrderMapping($grShopId, $externalOrderId);

    /**
     * @param string $grShopId
     * @param int $externalOrderId
     * @param string $grOrderId
     * @param string $payloadMd5
     * @return
     */
    public function saveOrderMapping($grShopId, $externalOrderId, $grOrderId, $payloadMd5);

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

    /**
     * @param int $accountId
     */
    public function markAccountAsInvalid($accountId);

    /**
     * @param $accountId
     */
    public function markAccountAsValid($accountId);

    /**
     * @param int $accountId
     */
    public function getInvalidAccountFirstOccurrenceDate($accountId);

    /**
     * @param int $accountId
     */
    public function disconnectAccount($accountId);

    /**
     * @return string
     */
    public function getOriginCustomFieldId();

    /**
     * @param string $id
     */
    public function setOriginCustomFieldId($id);

    public function clearOriginCustomField();

}
