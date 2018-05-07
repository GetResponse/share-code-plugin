<?php
namespace ShareCode\Cart;

/**
 * Class Product
 * @package ShareCode\Cart
 */
class Product
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $grVariantId;

    /** @var int */
    private $quantity;

    /** @var float */
    private $price;

    /** @var float */
    private $priceTax;

    /**
     * @param int $id
     * @param string $name
     * @param int $quantity
     * @param float $price
     * @param float $priceTax
     */
    public function __construct($id, $name, $quantity, $price, $priceTax)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->priceTax = $priceTax;
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
     * @return string
     */
    public function getGrVariantId()
    {
        return $this->grVariantId;
    }

    /**
     * @param string $grVariantId
     */
    public function setGrVariantId($grVariantId)
    {
        $this->grVariantId = $grVariantId;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPriceTax()
    {
        return $this->priceTax;
    }
}
