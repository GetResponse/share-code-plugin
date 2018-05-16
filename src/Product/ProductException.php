<?php
namespace GrShareCode\Product;

use GrShareCode\GrShareCodeException;

/**
 * Class ProductException
 * @package GrShareCode\Product
 */
class ProductException extends GrShareCodeException
{
    /**
     * @return ProductException
     */
    public static function createForInvalidName()
    {
        return new self('Invalid product name', self::INVALID_CATEGORY);
    }
}