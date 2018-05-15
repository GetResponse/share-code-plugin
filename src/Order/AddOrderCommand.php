<?php
namespace GrShareCode\Order;

/**
 * Class AddOrderCommand
 * @package GrShareCode\Order
 */
class AddOrderCommand
{
    /** @var Order */
    private $order;

    /** @var string */
    private $contactListId;

    /** @var string */
    private $email;

    /** @var string */
    private $shopId;

    /**
     * @param Order $order
     * @param string $email
     * @param string $contactListId
     * @param string $shopId
     */
    public function __construct(Order $order, $email, $contactListId, $shopId)
    {
        $this->order = $order;
        $this->contactListId = $contactListId;
        $this->email = $email;
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
    public function getContactListId()
    {
        return $this->contactListId;
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
    public function getShopId()
    {
        return $this->shopId;
    }

}

