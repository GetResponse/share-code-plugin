<?php
namespace GrShareCode\Product\Category;

use GrShareCode\TypedCollection;

/**
 * Class CategoryCollection
 * @package GrShareCode\Product\Category
 */
class CategoryCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Product\Category\Category');
    }
}