<?php
namespace GrShareCode\Product;

use GrShareCode\TypedCollection;

/**
 * Class ImagesCollection
 * @package GrShareCode\Product
 */
class ImagesCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Product\Image');
    }
}
