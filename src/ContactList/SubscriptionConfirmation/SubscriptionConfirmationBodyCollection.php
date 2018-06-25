<?php
namespace GrShareCode\ContactList\SubscriptionConfirmation;

use GrShareCode\TypedCollection;

/**
 * Class SubscriptionConfirmationBodyCollection
 * @package GrShareCode\ContactList\SubscriptionConfirmation
 */
class SubscriptionConfirmationBodyCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBody');
    }
}