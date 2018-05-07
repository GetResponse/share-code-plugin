<?php
namespace ShareCode\Cart;

/**
 * Class CartCommand
 * @package ShareCode\Cart
 */
class CartCommand
{
    /** @var string */
    private $email;

    /** @var string */
    private $campaignId;

    /** @var array */
    private $products;

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
     * @param string $campaignId
     * @param array $products
     * @param string $cartId
     * @param string $grCartId
     * @param string $currency
     * @param float $totalPrice
     * @param float $totalTaxPrice
     */
    public function __construct($email, $campaignId, $products, $cartId, $grCartId, $currency, $totalPrice, $totalTaxPrice)
    {
        $this->email = $email;
        $this->campaignId = $campaignId;
        $this->products = $products;
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
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
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
