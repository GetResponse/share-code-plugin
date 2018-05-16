<?php
namespace GrShareCode\Product\Variant\Images;

use GrShareCode\TypedCollection;

/**
 * Class ImagesCollection
 * @package GrShareCode\Product\Variant\Images
 */
class ImagesCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Product\Variant\Images\Image');
    }
}
