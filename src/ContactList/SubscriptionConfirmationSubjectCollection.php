<?php
namespace GrShareCode\ContactList;

use GrShareCode\TypedCollection;

/**
 * Class SubscriptionConfirmationSubjectCollection
 * @package GrShareCode\ContactList
 */
class SubscriptionConfirmationSubjectCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\SubscriptionConfirmationSubject');
    }
}