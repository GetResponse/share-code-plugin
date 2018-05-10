<?php
namespace GrShareCode\Product;

use GrShareCode\TypedCollection;

/**
 * Class ProductVariantsCollection
 * @package GrShareCode\Product
 */
class ProductVariantsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Product\ProductVariant');
    }
}
