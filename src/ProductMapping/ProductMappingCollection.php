<?php
namespace GrShareCode\ProductMapping;

use GrShareCode\TypedCollection;

/**
 * Class ProductMappingCollection
 * @package GrShareCode\ProductMapping
 */
class ProductMappingCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ProductMapping\ProductMapping');
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getIterator()->count() === 0;
    }
}
