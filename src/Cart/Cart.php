<?php
namespace GrShareCode\Cart;

use GrShareCode\Product\ProductsCollection;
use GrShareCode\Validation\Assert\Assert;

/**
 * Class Cart
 * @package GrShareCode\Cart
 */
class Cart
{
    /** @var string */
    private $cartId;

    /** @var ProductsCollection */
    private $products;

    /** @var string */
    private $currency;

    /** @var string */
    private $totalPrice;

    /** @var string */
    private $totalTaxPrice;

    /**
     * @param string $cartId
     * @param ProductsCollection $products
     * @param string $currency
     * @param string $totalPrice
     * @param string $totalTaxPrice
     */
    public function __construct($cartId, ProductsCollection $products, $currency, $totalPrice, $totalTaxPrice)
    {
        $this->setCartId($cartId);
        $this->setProducts($products);
        $this->setCurrency($currency);
        $this->setTotalPrice($totalPrice);
        $this->setTotalTaxPrice($totalTaxPrice);
    }

    /**
     * @param string $cartId
     */
    private function setCartId($cartId)
    {
        Assert::that($cartId)->notNull()->string();
        $this->cartId = $cartId;
    }

    /**
     * @param ProductsCollection $products
     */
    private function setProducts(ProductsCollection $products)
    {
        $this->products = $products;
    }

    /**
     * @param string $currency
     */
    private function setCurrency($currency)
    {
        Assert::that($currency)->notBlank()->string()->length(3);
        $this->currency = $currency;
    }

    /**
     * @param float $totalPrice
     */
    private function setTotalPrice($totalPrice)
    {
        Assert::that($totalPrice)->notNull()->float();
        $this->totalPrice = $totalPrice;
    }

    /**
     * @param null|float $totalTaxPrice
     */
    private function setTotalTaxPrice($totalTaxPrice)
    {
        Assert::that($totalTaxPrice)->nullOr()->float();
        $this->totalTaxPrice = $totalTaxPrice;
    }

    /**
     * @return string
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