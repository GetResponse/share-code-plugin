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

    /**
     * @return array
     */
    public function toRequestArray()
    {
        $categories = [];

        /** @var Category $category */
        foreach ($this->getIterator() as $category) {

            $categories[] = [
                'name' => $category->getName(),
                'parentId' => $category->getParentId(),
                'externalId' => $category->getExternalId(),
                'url' => $category->getUrl(),
            ];
        }

        return $categories;

    }
}