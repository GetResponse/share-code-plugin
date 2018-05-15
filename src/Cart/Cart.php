<?php
namespace GrShareCode\Cart;

use GrShareCode\Product\Product;
use GrShareCode\Product\ProductsCollection;

/**
 * Class Cart
 * @package GrShareCode\Cart
 */
class Cart
{

    /** @var Product */
    private $products;

    /** @var string */
    private $currency;

    /** @var string */
    private $totalPrice;

    /** @var string */
    private $totalTaxPrice;

    /** @var int */
    private $cartId;

    /**
     * @param int $cartId
     * @param ProductsCollection $products
     * @param string $currency
     * @param string $totalPrice
     * @param string $totalTaxPrice
     */
    public function __construct($cartId, ProductsCollection $products, $currency, $totalPrice, $totalTaxPrice)
    {
        $this->cartId = $cartId;
        $this->products = $products;
        $this->currency = $currency;
        $this->totalPrice = $totalPrice;
        $this->totalTaxPrice = $totalTaxPrice;
    }

    /**
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @return ProductsCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @return string
     */
    public function getTotalTaxPrice()
    {
        return $this->totalTaxPrice;
    }

}