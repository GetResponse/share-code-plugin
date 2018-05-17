<?php
namespace GrShareCode\Product;

use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Variant\Variant;
use GrShareCode\Validation\Assert\Assert;

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
     */
    public function __construct($externalId, $name, Variant $productVariant, CategoryCollection $categories)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
        $this->setProductVariant($productVariant);
        $this->setCategories($categories);
    }

    /**
     * @param int $externalId
     */
    private function setExternalId($externalId)
    {
        Assert::that($externalId)->notNull()->integer();
        $this->externalId = $externalId;
    }

    /**
     * @param string $name
     */
    private function setName($name)
    {
        Assert::that($name)->notBlank()->string();
        $this->name = $name;
    }

    /**
     * @param Variant $productVariant
     */
    private function setProductVariant(Variant $productVariant)
    {
        $this->productVariant = $productVariant;
    }

    /**
     * @param CategoryCollection $categories
     */
    private function setCategories(CategoryCollection $categories)
    {
        $this->categories = $categories;
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
        Assert::that($url)->nullOr()->string();
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
        Assert::that($type)->nullOr()->string();
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
        Assert::that($vendor)->nullOr()->string();
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
