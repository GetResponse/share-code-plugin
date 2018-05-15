<?php
namespace GrShareCode\Product;

use GrShareCode\TypedCollection;

/**
 * Class CategoriesCollection
 * @package GrShareCode\Product
 */
class CategoriesCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Product\Category');
    }
}
