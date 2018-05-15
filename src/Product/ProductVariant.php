<?php
namespace GrShareCode\Product;

/**
 * Class ProductVariant
 * @package GrShareCode\Product
 */
class ProductVariant
{
    private $id;

    private $name;

    private $price;

    private $priceTax;

    private $quantity;

    private $sku;

    /**
     * @param $id
     * @param $name
     * @param $price
     * @param $priceTax
     * @param $quantity
     */
    public function __construct($id, $name, $price, $priceTax, $quantity, $sku)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->priceTax = $priceTax;
        $this->quantity = $quantity;
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getPriceTax()
    {
        return $this->priceTax;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }
}
