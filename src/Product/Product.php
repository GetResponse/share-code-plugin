<?php
namespace GrShareCode\Product;

use GrShareCode\Product\Variant\Variant;

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

    /** @var Variant */
    private $productVariant;

    /**
     * @param int $id
     * @param string $name
     * @param Variant $productVariant
     * @throws ProductException
     */
    public function __construct($id, $name, Variant $productVariant)
    {
        $this->assertValidName($name);
        $this->id = $id;
        $this->name = $name;
        $this->productVariant = $productVariant;
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

}
