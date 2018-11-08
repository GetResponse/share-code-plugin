<?php
namespace GrShareCode\Order;

use GrShareCode\Address\Address;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Validation\Assert\Assert;

/**
 * Class Order
 * @package GrShareCode\Order
 */
class Order
{
    /** @var string */
    private $externalOrderId;

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
    private $externalCartId;

    /** @var string */
    private $description;

    /** @var float */
    private $shippingPrice;

    /** @var string */
    private $billingStatus;

    /** @var string */
    private $processedAt;

    /** @var Address */
    private $shippingAddress;

    /** @var Address */
    private $billingAddress;

    /**
     * @param string $externalOrderId
     * @param float $totalPrice
     * @param string $currency
     * @param ProductsCollection $products
     */
    public function __construct(
        $externalOrderId,
        $totalPrice,
        $currency,
        ProductsCollection $products
    ) {
        $this
            ->setExternalOrderId($externalOrderId)
            ->setTotalPrice($totalPrice)
            ->setCurrency($currency)
            ->setProducts($products);
    }

    /**
     * @param int $orderId
     * @return $this
     */
    private function setExternalOrderId($orderId)
    {
        $message = 'External order ID in Order should be a not blank string';
        Assert::that($orderId, $message)->notBlank()->string();
        $this->externalOrderId = $orderId;
        return $this;
    }

    /**
     * @param ProductsCollection $products
     * @return $this
     */
    private function setProducts(ProductsCollection $products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @param float $totalPrice
     * @return $this
     */
    private function setTotalPrice($totalPrice)
    {
        $message = 'Total price in Order should be a not null float';
        Assert::that($totalPrice, $message)->notNull()->float();
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    private function setCurrency($currency)
    {
        $message = 'Currency in Order should be a not blank string (3 chars)';
        Assert::that($currency, $message)->notBlank()->string()->length(3);
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param float $totalPriceTax
     * @return $this
     */
    public function setTotalPriceTax($totalPriceTax)
    {
        $message = 'Total price tax in Order should be null or float';
        Assert::that($totalPriceTax, $message)->nullOr()->float();
        $this->totalPriceTax = $totalPriceTax;
        return $this;
    }

    /**
     * @param string $orderUrl
     * @return $this
     */
    public function setOrderUrl($orderUrl)
    {
        $message = 'Order URL in Order should be null or string';
        Assert::that($orderUrl, $message)->notEmpty()->notNull()->string();
        $this->orderUrl = $orderUrl;
        return $this;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $message = 'Status in Order should be string';
        Assert::that($status, $message)->notEmpty()->notNull()->string();
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $cartId
     * @return $this
     */
    public function setExternalCartId($cartId)
    {
        $message = 'External cart ID in Order should be a not blank string';
        Assert::that($cartId, $message)->notBlank()->string();
        $this->externalCartId = $cartId;
        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $message = 'Description in Order should be string';
        Assert::that($description, $message)->notEmpty()->notNull()->string();
        $this->description = $description;
        return $this;
    }

    /**
     * @param float $shippingPrice
     * @return $this
     */
    public function setShippingPrice($shippingPrice)
    {
        $message = 'Shipping price in Order should be float';
        Assert::that($shippingPrice, $message)->notNull()->float();
        $this->shippingPrice = $shippingPrice;
        return $this;
    }

    /**
     * @param string $billingStatus
     * @return $this
     */
    public function setBillingStatus($billingStatus)
    {
        $message = 'Billing status in Order should be string';
        Assert::that($billingStatus, $message)->notEmpty()->notNull()->string();
        $this->billingStatus = $billingStatus;
        return $this;
    }

    /**
     * @param string $processedAt
     * @return $this
     */
    public function setProcessedAt($processedAt)
    {
        $message = 'Processed at in Order should be a ISO8601 date time';
        Assert::that($processedAt, $message)->date(\DateTime::ISO8601);
        $this->processedAt = $processedAt;
        return $this;
    }

    /**
     * @param Address $shippingAddress
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * @param Address $billingAddress
     * @return $this
     */
    public function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * @return int
     */
    public function getExternalOrderId()
    {
        return $this->externalOrderId;
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
     * @return string
     */
    public function getExternalCartId()
    {
        return $this->externalCartId;
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
    public function getBillingStatus()
    {
        return $this->billingStatus;
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