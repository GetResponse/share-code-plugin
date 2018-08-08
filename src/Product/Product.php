<?php
namespace GrShareCode\Product;

use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Variant\VariantsCollection;
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

    /** @var VariantsCollection */
    private $variants;

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
     * @param VariantsCollection $variants
     * @param CategoryCollection $categories
     */
    public function __construct($externalId, $name, VariantsCollection $variants, CategoryCollection $categories)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
        $this->setVariants($variants);
        $this->setCategories($categories);
    }

    /**
     * @param int $externalId
     */
    private function setExternalId($externalId)
    {
        $message = 'External ID in Product should be a not null integer';
        Assert::that($externalId, $message)->notNull()->integer();
        $this->externalId = $externalId;
    }

    /**
     * @param string $name
     */
    private function setName($name)
    {
        $message = 'Name in Product should be a not blank string';
        Assert::that($name, $message)->notBlank()->string();
        $this->name = $name;
    }

    /**
     * @param VariantsCollection $variants
     */
    private function setVariants(VariantsCollection $variants)
    {
        $this->variants = $variants;
    }

    /**
     * @return VariantsCollection
     */
    public function getVariants()
    {
        return $this->variants;
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
        $message = 'Url in Product should be null or string';
        Assert::that($url, $message)->nullOr()->string();
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
        $message = 'Type in Product should be null or string';
        Assert::that($type, $message)->nullOr()->string();
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
        $message = 'Vendor in Product should be null or string';
        Assert::that($vendor, $message)->nullOr()->string();
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
     * @param VariantsCollection $variants
     * @return Product
     */
    public function withVariants(VariantsCollection $variants)
    {
        return new self($this->getExternalId(), $this->getName(), $variants, $this->getCategories());
    }
}
