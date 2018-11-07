<?php
namespace GrShareCode\Order\Command;

use GrShareCode\Order\Order;

/**
 * Class EditOrderCommand
 * @package GrShareCode\Order\Command
 */
class EditOrderCommand
{
    /** @var Order */
    private $order;
    /** @var string */
    private $shopId;

    /**
     * @param Order $order
     * @param string $shopId
     */
    public function __construct(Order $order, $shopId)
    {
        $this->order = $order;
        $this->shopId = $shopId;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }
}