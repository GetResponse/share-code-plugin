<?php
namespace GrShareCode\Product;

/**
 * Class Product
 * @package GrShareCode\Cart
 */
class Product
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var ProductVariantsCollection */
    private $productVariants;

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
     * @param ProductVariantsCollection $productVariants
     * @param string $url
     * @param string $type
     * @param string $vendor
     * @param CategoriesCollection $categories
     */
    public function __construct($id, $name, ProductVariantsCollection $productVariants, $url, $type, $vendor, CategoriesCollection $categories)
    {
        $this->id = $id;
        $this->name = $name;
        $this->productVariants = $productVariants;
        $this->url = $url;
        $this->type = $type;
        $this->vendor = $vendor;
        $this->categories = $categories;
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
     * @return ProductVariantsCollection
     */
    public function getProductVariants()
    {
        return $this->productVariants;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @return CategoriesCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
