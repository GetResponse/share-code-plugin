<?php
namespace GrShareCode\Cart;

use GrShareCode\Validation\Assert\Assert;

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
        $this->setCart($cart);
        $this->setEmail($email);
        $this->setContactListId($contactListId);
        $this->setShopId($shopId);
    }

    /**
     * @param Cart $cart
     */
    private function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @param string $email
     */
    private function setEmail($email)
    {
        $message = 'Email in AddCartCommand should be valid';
        Assert::that($email, $message)->email();
        $this->email = $email;
    }

    /**
     * @param string $contactListId
     */
    private function setContactListId($contactListId)
    {
        $message = 'Contact list ID in AddCartCommand should be a not blank string';
        Assert::that($contactListId, $message)->notBlank()->string();
        $this->contactListId = $contactListId;
    }

    /**
     * @param string $shopId
     */
    private function setShopId($shopId)
    {
        $message = 'Shop ID in AddCartCommand should be a not blank string';
        Assert::that($shopId, $message)->notBlank()->string();
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
