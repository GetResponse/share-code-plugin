<?php
namespace GrShareCode\Product\Category;

use GrShareCode\Validation\Assert\Assert;

/**
 * Class Category
 * @package GrShareCode\Product\Category
 */
class Category
{
    /** @var string */
    private $name;

    /** @var string */
    private $parentId;

    /** @var string */
    private $externalId;

    /** @var string */
    private $url;

    /** @var boolean */
    private $isDefault;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
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
     * @param null|boolean $default
     * @return $this
     */
    public function setDefault($default)
    {
        Assert::that($default)->nullOr()->boolean();
        $this->isDefault = $default;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param null|string $parentId
     * @return $this
     */
    public function setParentId($parentId)
    {
        Assert::that($parentId)->nullOr()->string();
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId)
    {
        Assert::that($externalId)->nullOr()->string();
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     * @return $this
     */
    public function setUrl($url)
    {
        Assert::that($url)->nullOr()->string();
        $this->url = $url;

        return $this;
    }

}