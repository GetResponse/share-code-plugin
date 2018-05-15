<?php
namespace GrShareCode\Order;

use GrShareCode\Address\Address;
use GrShareCode\Product\ProductsCollection;

/**
 * Class Order
 * @package GrShareCode\Order
 */
class Order
{
    /** @var int */
    private $orderId;

    /** @var ProductsCollection */
    private $products;

    /** @var float */
    private $totalPrice;

    /** @var float */
    private $totalPriceTax;

    /** @var string */
    private $orderUrl;

    /** @var string */
    private $currency;

    /** @var string */
    private $status;

    /** @var int */
    private $cartId;

    /** @var string */
    private $description;

    /** @var float */
    private $shippingPrice;

    /** @var float */
    private $billingPrice;

    /** @var string */
    private $processedAt;

    /** @var Address */
    private $shippingAddress;

    /** @var Address */
    private $billingAddress;

    /**
     * @param int $orderId
     * @param ProductsCollection $products
     * @param float $totalPrice
     * @param float $totalPriceTax
     * @param string $orderUrl
     * @param string $currency
     * @param string $status
     * @param int $cartId
     * @param string $description
     * @param float $shippingPrice
     * @param float $billingPrice
     * @param string $processedAt
     * @param Address $shippingAddress
     * @param Address $billingAddress
     */
    public function __construct(
        $orderId,
        ProductsCollection $products,
        $totalPrice,
        $totalPriceTax,
        $orderUrl,
        $currency,
        $status,
        $cartId,
        $description,
        $shippingPrice,
        $billingPrice,
        $processedAt,
        Address $shippingAddress,
        Address $billingAddress
    ) {
        $this->orderId = $orderId;
        $this->products = $products;
        $this->totalPrice = $totalPrice;
        $this->totalPriceTax = $totalPriceTax;
        $this->orderUrl = $orderUrl;
        $this->currency = $currency;
        $this->status = $status;
        $this->cartId = $cartId;
        $this->description = $description;
        $this->shippingPrice = $shippingPrice;
        $this->billingPrice = $billingPrice;
        $this->processedAt = $processedAt;
        $this->shippingAddress = $shippingAddress;
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return ProductsCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @return float
     */
    public function getTotalPriceTax()
    {
        return $this->totalPriceTax;
    }

    /**
     * @return string
     */
    public function getOrderUrl()
    {
        return $this->orderUrl;
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }

    /**
     * @return float
     */
    public function getBillingPrice()
    {
        return $this->billingPrice;
    }

    /**
     * @return string
     */
    public function getProcessedAt()
    {
        return $this->processedAt;
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }


}