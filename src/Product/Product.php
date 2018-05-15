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
     * @param string $name
     * @param ProductVariantsCollection $productVariants
     * @param string $externalId
     * @param string $url
     * @param string $type
     * @param string $vendor
     * @param CategoriesCollection $categories
     */
    public function __construct($name, ProductVariantsCollection $productVariants, $externalId = '', $url = '', $type = '', $vendor = '', CategoriesCollection $categories = null)
    {
        $this->name = $name;
        $this->productVariants = $productVariants;
        $this->externalId = $externalId;
        $this->url = $url;
        $this->type = $type;
        $this->vendor = $vendor;
        $this->categories = $categories;
    }

    /**
     * @return int
     */
    public function getExternalId()
    {
        return $this->externalId;
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
