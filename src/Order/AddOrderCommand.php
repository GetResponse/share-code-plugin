<?php
namespace GrShareCode\Order;

use GrShareCode\Validation\Assert\Assert;

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

    /** @var bool */
    private $skipAutomation;

    /**
     * @param Order $order
     * @param string $email
     * @param string $contactListId
     * @param string $shopId
     */
    public function __construct(
        Order $order,
        $email,
        $contactListId,
        $shopId
    ) {
        $this->order = $order;
        $this->skipAutomation = self::SKIP_AUTOMATION_FALSE;
        $this->setEmail($email);
        $this->setContactListId($contactListId);
        $this->setShopId($shopId);
    }

    /**
     * @param string $email
     */
    private function setEmail($email)
    {
        Assert::that($email)->email();
        $this->email = $email;
    }

    /**
     * @param string $contactListId
     */
    private function setContactListId($contactListId)
    {
        Assert::that($contactListId)->notBlank()->string();
        $this->contactListId = $contactListId;
    }

    /**
     * @param string $shopId
     */
    private function setShopId($shopId)
    {
        Assert::that($shopId)->notBlank()->string();
        $this->shopId = $shopId;
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

    public function setToSkipAutomation()
    {
        $this->skipAutomation = self::SKIP_AUTOMATION_TRUE;
    }

}

