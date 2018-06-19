<?php
namespace GrShareCode\ContactList\SubscriptionConfirmation;

use GrShareCode\TypedCollection;

/**
 * Class SubscriptionConfirmationSubjectCollection
 * @package GrShareCode\ContactList\SubscriptionConfirmation
 */
class SubscriptionConfirmationSubjectCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubject');
    }
}