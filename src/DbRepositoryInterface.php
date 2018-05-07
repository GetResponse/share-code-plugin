<?php
namespace ShareCode;

/**
 * Class DbRepositoryInterface
 * @package ShareCode\Cart
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
}