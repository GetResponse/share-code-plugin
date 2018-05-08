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
    private $productsCollection;

    private $cartId;

    /** @var string */
    private $grCartId;

    /** @var string */
    private $currency;

    /** @var float */
    private $totalPrice;

    /** @var float */
    private $totalTaxPrice;

    /**
     * @param string $email
     * @param string $listId
     * @param ProductsCollection $products
     * @param string $cartId
     * @param string $grCartId
     * @param string $currency
     * @param float $totalPrice
     * @param float $totalTaxPrice
     */
    public function __construct($email, $listId, ProductsCollection $products, $cartId, $grCartId, $currency, $totalPrice, $totalTaxPrice)
    {
        $this->email = $email;
        $this->listId = $listId;
        $this->productsCollection = $products;
        $this->grCartId = $grCartId;
        $this->currency = $currency;
        $this->totalPrice = $totalPrice;
        $this->totalTaxPrice = $totalTaxPrice;
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
        return $this->productsCollection;
    }

    /**
     * @return string
     */
    public function getGrCartId()
    {
        return $this->grCartId;
    }

    /**
     * @param string $grCartId
     */
    public function setGrCartId($grCartId)
    {
        $this->grCartId = $grCartId;
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
     * @return mixed
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
}
