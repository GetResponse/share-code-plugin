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

    /** @var float */
    private $billingStatus;

    /** @var string */
    private $processedAt;

    /** @var Address */
    private $shippingAddress;

    /** @var Address */
    private $billingAddress;

    /**
     * @param string $externalOrderId
     * @param ProductsCollection $products
     * @param float $totalPrice
     * @param float $totalPriceTax
     * @param string $orderUrl
     * @param string $currency
     * @param string $status
     * @param string $externalCartId
     * @param string $description
     * @param float $shippingPrice
     * @param float $billingStatus
     * @param string $processedAt
     * @param Address $shippingAddress
     * @param Address $billingAddress
     */
    public function __construct(
        $externalOrderId,
        ProductsCollection $products,
        $totalPrice,
        $totalPriceTax,
        $orderUrl,
        $currency,
        $status,
        $externalCartId,
        $description,
        $shippingPrice,
        $billingStatus,
        $processedAt,
        Address $shippingAddress,
        Address $billingAddress
    ) {

        $this->setExternalOrderId($externalOrderId);
        $this->setProducts($products);
        $this->setTotalPrice($totalPrice);
        $this->setTotalPriceTax($totalPriceTax);
        $this->setOrderUrl($orderUrl);
        $this->setCurrency($currency);
        $this->setStatus($status);
        $this->setExternalCartId($externalCartId);
        $this->setDescription($description);
        $this->setShippingPrice($shippingPrice);
        $this->setBillingStatus($billingStatus);
        $this->setProcessedAt($processedAt);
        $this->setShippingAddress($shippingAddress);
        $this->setBillingAddress($shippingAddress);
    }

    /**
     * @param int $orderId
     */
    private function setExternalOrderId($orderId)
    {
        Assert::that($orderId)->notBlank()->string();
        $this->externalOrderId = $orderId;
    }

    /**
     * @param ProductsCollection $products
     */
    private function setProducts(ProductsCollection $products)
    {
        $this->products = $products;
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
     * @param null|float $totalPriceTax
     */
    private function setTotalPriceTax($totalPriceTax)
    {
        Assert::that($totalPriceTax)->nullOr()->float();
        $this->totalPriceTax = $totalPriceTax;
    }

    /**
     * @param null|string $orderUrl
     */
    private function setOrderUrl($orderUrl)
    {
        Assert::that($orderUrl)->nullOr()->string();
        $this->orderUrl = $orderUrl;
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
     * @param null|string $status
     */
    private function setStatus($status)
    {
        Assert::that($status)->nullOr()->string();
        $this->status = $status;
    }

    /**
     * @param string $cartId
     */
    private function setExternalCartId($cartId)
    {
        Assert::that($cartId)->notBlank()->string();
        $this->externalCartId = $cartId;
    }

    /**
     * @param null|string $description
     */
    private function setDescription($description)
    {
        Assert::that($description)->nullOr()->string();
        $this->description = $description;
    }

    /**
     * @param null|float $shippingPrice
     */
    private function setShippingPrice($shippingPrice)
    {
        Assert::that($shippingPrice)->nullOr()->float();
        $this->shippingPrice = $shippingPrice;
    }

    /**
     * @param null|string $billingStatus
     */
    private function setBillingStatus($billingStatus)
    {
        Assert::that($billingStatus)->nullOr()->string();
        $this->billingStatus = $billingStatus;
    }

    /**
     * @param string $processedAt
     */
    private function setProcessedAt($processedAt)
    {
        Assert::that($processedAt)->date(\DateTime::ISO8601);
        $this->processedAt = $processedAt;
    }

    /**
     * @param Address $shippingAddress
     */
    private function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @param Address $billingAddress
     */
    private function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
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