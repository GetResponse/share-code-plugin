<?php
namespace GrShareCode\Export;

use GrShareCode\Cart\Cart;
use GrShareCode\Contact\CustomFieldsCollection;
use GrShareCode\Order\Order;

/**
 * Class ExportContactCommand
 * @package GrShareCode\Contact
 */
class ExportContactCommand
{
    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var CustomFieldsCollection */
    private $customFieldsCollection;

    /** @var Cart */
    private $cart;

    /** @var Order */
    private $order;

    /**
     * @param string $email
     * @param string $name
     * @param CustomFieldsCollection $customFieldsCollection
     * @param Cart $cart
     * @param Order $order
     */
    public function __construct($email, $name, CustomFieldsCollection $customFieldsCollection = null, Cart $cart = null, Order $order = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->customFieldsCollection = $customFieldsCollection;
        $this->cart = $cart;
        $this->order = $order;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return CustomFieldsCollection
     */
    public function getCustomFieldsCollection()
    {
        return $this->customFieldsCollection;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

}
