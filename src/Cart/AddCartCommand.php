<?php
namespace GrShareCode\Cart;

/**
 * Class AddCartCommand
 * @package GrShareCode\Cart
 */
class AddCartCommand
{
    /** @var string */
    private $email;

    /** @var string */
    private $contactListId;

    /** @var string */
    private $shopId;

    /** @var Cart */
    private $cart;

    /**
     * @param string $email
     * @param string $shopId
     * @param string $contactListId
     * @param Cart $cart
     */
    public function __construct($email, $shopId, $contactListId, Cart $cart)
    {
        $this->email = $email;
        $this->shopId = $shopId;
        $this->contactListId = $contactListId;
        $this->cart = $cart;
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
    public function getContactListId()
    {
        return $this->contactListId;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

}
