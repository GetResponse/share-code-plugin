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

    /**
     * @param int $id
     * @param string $name
     * @param ProductVariantsCollection $productVariants
     */
    public function __construct($id, $name, ProductVariantsCollection $productVariants)
    {
        $this->id = $id;
        $this->name = $name;
        $this->productVariants = $productVariants;
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
}
