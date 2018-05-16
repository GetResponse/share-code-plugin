<?php
namespace GrShareCode\Product\Variant;

use GrShareCode\GrShareCodeException;

/**
 * Class VariantException
 * @package GrShareCode\Product\Variant
 */
class VariantException extends GrShareCodeException
{
    /**
     * @return VariantException
     */
    public static function createForInvalidName()
    {
        return new self('Invalid variant name', self::INVALID_VARIANT);
    }

    /**
     * @return VariantException
     */
    public static function createForInvalidPrice()
    {
        return new self('Invalid variant price', self::INVALID_VARIANT);
    }

    /**
     * @return VariantException
     */
    public static function createForInvalidPriceTax()
    {
        return new self('Invalid variant tax', self::INVALID_VARIANT);
    }

    /**
     * @return VariantException
     */
    public static function createForInvalidSku()
    {
        return new self('Invalid variant sku', self::INVALID_VARIANT);
    }
}
