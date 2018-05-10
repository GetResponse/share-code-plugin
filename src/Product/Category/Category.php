<?php
namespace GrShareCode\Product\Category;

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

    /**
     * @param string $name
     * @param string $parentId
     * @param string $externalId
     * @param string $url
     * @throws CategoryException
     */
    public function __construct($name, $parentId, $externalId, $url)
    {
        $this->assertValidName($name);

        $this->name = $name;
        $this->parentId = $parentId;
        $this->externalId = $externalId;
        $this->url = $url;
    }

    /**
     * @param string $name
     * @throws CategoryException
     */
    private function assertValidName($name)
    {
        if (empty($name)) {
            throw CategoryException::createForInvalidName();
        }
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