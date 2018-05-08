<?php
namespace GrShareCode;

/**
 * Class DbRepositoryInterface
 * @package GrShareCode\Cart
 */
interface DbRepositoryInterface
{
    /**
     * @param int $id
     * @return string|null
     */
    public function getProductVariantById($id);

    /**
     * @param int $productId
     * @param string $grVariantId
     */
    public function saveProductVariant($productId, $grVariantId);

    /**
     * @param int $cartId
     * @param string $grCartId
     */
    public function saveCartMapping($cartId, $grCartId);
}