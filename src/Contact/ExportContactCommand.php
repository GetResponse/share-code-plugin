<?php
namespace GrShareCode\Contact;

use GrShareCode\Cart\Cart;

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

    /**
     * @param string $email
     * @param string $name
     * @param CustomFieldsCollection $customFieldsCollection
     * @param Cart $cart
     */
    public function __construct($email, $name, $customFieldsCollection, Cart $cart)
    {
        $this->email = $email;
        $this->name = $name;
        $this->customFieldsCollection = $customFieldsCollection;
        $this->cart = $cart;
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

}
