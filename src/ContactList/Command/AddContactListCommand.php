<?php
namespace GrShareCode\ContactList\Command;

use GrShareCode\Validation\Assert\Assert;

/**
 * Class AddContactListCommand
 * @package GrShareCode\ContactList\Command
 */
class AddContactListCommand
{
    /** @var string */
    private $contactListName;

    /** @var string */
    private $fromFieldId;

    /** @var string */
    private $replyToId;

    /** @var string */
    private $subscriptionConfirmationBodyId;

    /** @var string */
    private $subscriptionConfirmationSubjectId;

    /** @var string */
    private $languageCode;

    /**
     * @param string $contactListName
     * @param string $fromFieldId
     * @param string $replyToId
     * @param string $subscriptionConfirmationBodyId
     * @param string $subscriptionConfirmationSubjectId
     * @param string $languageCode
     *
     */
    public function __construct(
        $contactListName,
        $fromFieldId,
        $replyToId,
        $subscriptionConfirmationBodyId,
        $subscriptionConfirmationSubjectId,
        $languageCode
    ) {
        $this->setContactListName($contactListName);
        $this->fromFieldId = $fromFieldId;
        $this->replyToId = $replyToId;
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
    public function getFromFieldId()
    {
        return $this->fromFieldId;
    }

    /**
     * @return string
     */
    public function getReplyToId()
    {
        return $this->replyToId;
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