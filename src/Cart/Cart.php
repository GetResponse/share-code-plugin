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
        $message = 'Cart ID in Cart should be a not null string';
        Assert::that($cartId, $message)->notNull()->string();
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
        $message = 'Currency in Cart should be a not blank string (3 chars)';
        Assert::that($currency, $message)->notBlank()->string()->length(3);
        $this->currency = $currency;
    }

    /**
     * @param float $totalPrice
     */
    private function setTotalPrice($totalPrice)
    {
        $message = 'Total price in Cart should be a not null float';
        Assert::that($totalPrice, $message)->notNull()->float();
        $this->totalPrice = $totalPrice;
    }

    /**
     * @param null|float $totalTaxPrice
     */
    private function setTotalTaxPrice($totalTaxPrice)
    {
        $message = 'Total tax price in Cart should be null or float';
        Assert::that($totalTaxPrice, $message)->nullOr()->float();
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