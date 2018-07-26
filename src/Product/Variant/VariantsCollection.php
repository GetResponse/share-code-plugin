<?php
namespace GrShareCode\Product\Variant;

use GrShareCode\TypedCollection;

/**
 * Class VariantsCollection
 * @package GrShareCode\Product\Variant
 */
class VariantsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Product\Variant\Variant');
    }

    /**
     * @return array
     */
    public function toRequestArray()
    {
        $variants = [];

        /** @var Variant $variant */
        foreach ($this->getIterator() as $variant) {
            $variants[] = $variant->toRequestArray();
        }

        return $variants;
    }
}