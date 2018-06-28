<?php
namespace GrShareCode\ContactList\SubscriptionConfirmation;

/**
 * Class SubscriptionConfirmationSubject
 * @package GrShareCode\ContactList\SubscriptionConfirmation
 */
class SubscriptionConfirmationSubject
{
    /** @var string */
    private $id;

    /** @var string */
    private $subject;

    /**
     * @param string $id
     * @param string $subject
     */
    public function __construct($id, $subject)
    {
        $this->id = $id;
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }


}