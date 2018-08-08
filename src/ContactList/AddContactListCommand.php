<?php
namespace GrShareCode\ContactList;

use GrShareCode\Validation\Assert\Assert;

/**
 * Class AddContactListCommand
 * @package GrShareCode\ContactList
 */
class AddContactListCommand
{
    /** @var string */
    private $contactListName;

    /** @var string */
    private $fromField;

    /** @var string */
    private $replyTo;

    /** @var string */
    private $subscriptionConfirmationBodyId;

    /** @var string */
    private $subscriptionConfirmationSubjectId;

    /** @var string */
    private $languageCode;

    /**
     * @param string $contactListName
     * @param string $fromField
     * @param string $replyTo
     * @param string $subscriptionConfirmationBodyId
     * @param string $subscriptionConfirmationSubjectId
     * @param string $languageCode
     */
    public function __construct(
        $contactListName,
        $fromField,
        $replyTo,
        $subscriptionConfirmationBodyId,
        $subscriptionConfirmationSubjectId,
        $languageCode
    ) {
        $this->setContactListName($contactListName);
        $this->fromField = $fromField;
        $this->replyTo = $replyTo;
        $this->subscriptionConfirmationBodyId = $subscriptionConfirmationBodyId;
        $this->subscriptionConfirmationSubjectId = $subscriptionConfirmationSubjectId;
        $this->languageCode = $languageCode;
    }

    /**
     * @param string $contactListName
     */
    private function setContactListName($contactListName)
    {
        $message = 'Contact list name in AddContactListCommand should be a not blank string';
        Assert::that($contactListName, $message)->notBlank()->string();
        $this->contactListName = $contactListName;
    }

    /**
     * @return string
     */
    public function getContactListName()
    {
        return $this->contactListName;
    }

    /**
     * @return string
     */
    public function getFromField()
    {
        return $this->fromField;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @return string
     */
    public function getSubscriptionConfirmationBodyId()
    {
        return $this->subscriptionConfirmationBodyId;
    }

    /**
     * @return string
     */
    public function getSubscriptionConfirmationSubjectId()
    {
        return $this->subscriptionConfirmationSubjectId;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

}