<?php
namespace GrShareCode\ContactList;

use GrShareCode\TypedCollection;

/**
 * Class SubscriptionConfirmationBodyCollection
 * @package GrShareCode\ContactList
 */
class SubscriptionConfirmationBodyCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\SubscriptionConfirmationBody');
    }
}