<?php
namespace GrShareCode\Shop;

use GrShareCode\TypedCollection;

/**
 * Class ShopsCollection
 * @package GrShareCode\Shop
 */
class ShopsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Shop\Shop');
    }
}