<?php
namespace GrShareCode\Order;

/**
 * Class AddOrderCommand
 * @package GrShareCode\Order
 */
class AddOrderCommand
{
    const SKIP_AUTOMATION_FALSE = false;
    const SKIP_AUTOMATION_TRUE = true;

    /** @var Order */
    private $order;

    /** @var string */
    private $contactListId;

    /** @var string */
    private $email;

    /** @var string */
    private $shopId;
    /**
     * @var bool
     */
    private $skipAutomation;

    /**
     * @param Order $order
     * @param string $email
     * @param string $contactListId
     * @param string $shopId
     * @param bool $skipAutomation
     */
    public function __construct(
        Order $order,
        $email,
        $contactListId,
        $shopId,
        $skipAutomation = self::SKIP_AUTOMATION_FALSE
    ) {
        $this->order = $order;
        $this->contactListId = $contactListId;
        $this->email = $email;
        $this->shopId = $shopId;
        $this->skipAutomation = $skipAutomation;
    }

    /**
     * @return bool
     */
    public function skipAutomation()
    {
        return $this->skipAutomation;
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

