<?php
namespace GrShareCode\Product;

use GrShareCode\TypedCollection;

/**
 * Class ProductsCollection
 * @package GrShareCode\Product
 */
class ProductsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType(Product::class);
    }
}
