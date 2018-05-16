<?php
namespace GrShareCode\Product\Category;

use GrShareCode\GrShareCodeException;

/**
 * Class CategoryException
 * @package GrShareCode\Api
 */
class CategoryException extends GrShareCodeException
{
    /**
     * @return CategoryException
     */
    public static function createForInvalidName()
    {
        return new self('Invalid category name', self::INVALID_CATEGORY);
    }
}
