<?php
namespace GrShareCode\Order;

use GrShareCode\TypedCollection;

/**
 * Class OrderCollection
 * @package GrShareCode\Order
 */
class OrderCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType(Order::class);
    }
}