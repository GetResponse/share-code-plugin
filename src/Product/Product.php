<?php
namespace GrShareCode\Product;

use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Variant\Variant;

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

    /** @var CategoryCollection */
    private $categories;

    /**
     * @param int $externalId
     * @param string $name
     * @param Variant $productVariant
     * @param CategoryCollection $categories
     * @throws ProductException
     */
    public function __construct(
        $externalId,
        $name,
        Variant $productVariant,
        CategoryCollection $categories
    ) {
        $this->assertValidName($name);

        $this->externalId = $externalId;
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
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return Variant
     */
    public function getProductVariant()
    {
        return $this->productVariant;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Product
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     * @return Product
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return CategoryCollection
     */
    public function getCategories()
    {
        return $this->categories;
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
