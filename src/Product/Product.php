<?php
namespace GrShareCode\Product;

/**
 * Class Product
 * @package GrShareCode\Cart
 */
class Product
{
    /** @var string */
    private $externalId;

    /** @var string */
    private $name;

    /** @var Variant */
    private $productVariant;

    /** @var string */
    private $url;

    /** @var string */
    private $type;

    /** @var string */
    private $vendor;

    /** @var CategoriesCollection */
    private $categories;

    /**
     * @param int $id
     * @param string $name
     * @param Variant $productVariant
     * @param CategoryCollection $categories
     * @throws ProductException
     */
    public function __construct($name, ProductVariantsCollection $productVariants, $externalId = '', $url = '', $type = '', $vendor = '', CategoriesCollection $categories = null)
    public function __construct($id, $name, Variant $productVariant, CategoryCollection $categories)
    {
        $this->assertValidName($name);
        $this->id = $id;
        $this->name = $name;
        $this->productVariant = $productVariant;
        $this->categories = $categories;
    }

    /**
     * @param string $name
     * @throws ProductException
     */
    private function assertValidName($name)
    {
        if (empty($name)) {
            throw ProductException::createForInvalidName();
        }
    }

    /**
     * @return CategoryCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Variant
     */
    public function getVariant()
    {
        return $this->productVariant;
    }

}
