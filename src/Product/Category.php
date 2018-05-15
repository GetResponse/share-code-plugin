<?php
namespace GrShareCode\Product;

/**
 * Class Category
 * @package GrShareCode\Product
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

    /**
     * @param string $name
     * @param string $parentId
     * @param string $externalId
     * @param string $url
     */
    public function __construct($name, $parentId, $externalId, $url)
    {
        $this->name = $name;
        $this->parentId = $parentId;
        $this->externalId = $externalId;
        $this->url = $url;
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
}
