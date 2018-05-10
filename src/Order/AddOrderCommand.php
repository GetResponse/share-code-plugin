<?php
namespace GrShareCode\Order;

use GrShareCode\Address\Address;
use GrShareCode\Product\ProductsCollection;

/**
 * Class AddOrderCommand
 * @package GrShareCode\Order
 */
class AddOrderCommand
{
    /** @var int */
    private $orderId;

    /** @var string */
    private $listId;

    /** @var string */
    private $email;

    /** @var string */
    private $shopId;

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

    /** @var string */
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
     * AddOrderCommand constructor.
     * @param int $orderId
     * @param string $listId
     * @param string $email
     * @param string $shopId
     * @param ProductsCollection $products
     * @param float $totalPrice
     * @param float $totalPriceTax
     * @param string $orderUrl
     * @param string $currency
     * @param string $status
     * @param string $cartId
     * @param string $description
     * @param float $shippingPrice
     * @param float $billingPrice
     * @param string $processedAt
     * @param Address $shippingAddress
     * @param Address $billingAddress
     */
    public function __construct(
        $orderId,
        $listId,
        $email,
        $shopId,
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
        $this->listId = $listId;
        $this->email = $email;
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
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * @return string
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
    public function getShopId()
    {
        return $this->shopId;
    }
}

