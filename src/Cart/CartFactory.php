<?php
namespace GrShareCode\Cart;

use GrShareCode\Export\HistoricalOrder\HistoricalOrder;

/**
 * Class CartFactory
 * @package GrShareCode\Cart
 */
class CartFactory
{
    /**
     * @param HistoricalOrder $historicalOrder
     * @return Cart
     */
    public static function createFromHistoricalOrder(HistoricalOrder $historicalOrder)
    {
        return new Cart(
            $historicalOrder->getCartId(),
            $historicalOrder->getProducts(),
            $historicalOrder->getCurrency(),
            $historicalOrder->getTotalPrice(),
            $historicalOrder->getTotalPriceTax()
        );
    }
}