<?php
namespace GrShareCode\Export\HistoricalOrder;

use GrShareCode\Address\Address;
use GrShareCode\Cart\Cart;
use GrShareCode\Order\Order;
use GrShareCode\Product\ProductsCollection;

/**
 * Class HistoricalOrder
 * @package GrShareCode\Export\HistoricalOrder
 */
class HistoricalOrder extends Order
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @param int $externalOrderId
     * @param ProductsCollection $products
     * @param float $totalPrice
     * @param float $totalPriceTax
     * @param string $orderUrl
     * @param string $currency
     * @param string $status
     * @param int $externalCartId
     * @param string $description
     * @param float $shippingPrice
     * @param float $billingStatus
     * @param string $processedAt
     * @param Address $shippingAddress
     * @param Address $billingAddress
     * @param Cart $cart
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
        Address $billingAddress,
        Cart $cart
    ) {
        parent::__construct(
            $externalOrderId,
            $products,
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
            $shippingAddress,
            $billingAddress
        );

        $this->cart = $cart;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }


}