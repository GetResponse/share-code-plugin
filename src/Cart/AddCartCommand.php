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
     * @param Cart $cart
     * @param string $email
     * @param string $contactListId
     * @param string $shopId
     */
    public function __construct(Cart $cart, $email, $contactListId, $shopId)
    {
        $this->cart = $cart;
        $this->email = $email;
        $this->contactListId = $contactListId;
        $this->shopId = $shopId;
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
