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
        $message = 'Name in Category should be a not blank string';
        Assert::that($name, $message)->notBlank()->string();
        $this->name = $name;
    }

    /**
     * @param null|boolean $default
     * @return $this
     */
    public function setDefault($default)
    {
        $message = 'Default in Category should be null or boolean';
        Assert::that($default, $message)->nullOr()->boolean();
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
        $message = 'Parent ID in Category should be null or string';
        Assert::that($parentId, $message)->nullOr()->string();
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
        $message = 'External ID in Category should be null or string';
        Assert::that($externalId, $message)->nullOr()->string();
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
        $message = 'URL in Category should be null or string';
        Assert::that($url, $message)->nullOr()->string();
        $this->url = $url;
        return $this;
    }

}