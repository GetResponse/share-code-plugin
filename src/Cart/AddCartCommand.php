<?php
namespace GrShareCode\Cart;

use GrShareCode\Product\ProductsCollection;

/**
 * Class AddCartCommand
 * @package GrShareCode\Cart
 */
class AddCartCommand
{
    /** @var string */
    private $email;

    /** @var string */
    private $listId;

    /** @var ProductsCollection */
    private $products;

    /** @var string|int */
    private $cartId;

    /** @var string */
    private $currency;

    /** @var float */
    private $totalPrice;

    /** @var float */
    private $totalTaxPrice;

    /** @var string */
    private $shopId;

    /** @var string */
    private $cartUrl;

    /**
     * @param string $email
     * @param string $shopId
     * @param string $listId
     * @param ProductsCollection $products
     * @param string $cartId
     * @param string $currency
     * @param float $totalPrice
     * @param float $totalTaxPrice
     * @param string $cartUrl
     */
    public function __construct($email, $shopId, $listId, ProductsCollection $products, $cartId, $currency, $totalPrice, $totalTaxPrice, $cartUrl = '')
    {
        $this->email = $email;
        $this->shopId = $shopId;
        $this->listId = $listId;
        $this->products = $products;
        $this->cartId = $cartId;
        $this->currency = $currency;
        $this->totalPrice = $totalPrice;
        $this->totalTaxPrice = $totalTaxPrice;
        $this->cartUrl = $cartUrl;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
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
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @return string|int
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @return float
     */
    public function getTotalTaxPrice()
    {
        return $this->totalTaxPrice;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @return string
     */
    public function getCartUrl()
    {
        return $this->cartUrl;
    }
}
