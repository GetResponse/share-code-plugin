<?php
namespace GrShareCode\ContactList;

use GrShareCode\ContactList\Command\AddContactListCommand;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBody;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBodyCollection;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubject;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubjectCollection;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;

/**
 * Class ContactListService
 * @package GrShareCode\ContactList
 */
class ContactListService
{
    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     */
    public function __construct(GetresponseApiClient $getresponseApiClient)
    {
        $this->getresponseApiClient = $getresponseApiClient;
    }

    /**
     * @return FromFieldsCollection
     * @throws GetresponseApiException
     */
    public function getFromFields()
    {
        $fromFieldsList = $this->getresponseApiClient->getFromFields();
        $collection = new FromFieldsCollection();

        foreach ($fromFieldsList as $fromField) {
            $collection->add(new FromFields(
                $fromField['fromFieldId'],
                $fromField['name'],
                $fromField['email']
            ));
        }

        return $collection;
    }

    /**
     * @return SubscriptionConfirmationSubjectCollection
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationSubjects()
    {
        $subjects = $this->getresponseApiClient->getSubscriptionConfirmationSubject();
        $collection = new SubscriptionConfirmationSubjectCollection();

        foreach ($subjects as $subject) {
            $collection->add(new SubscriptionConfirmationSubject(
                $subject['subscriptionConfirmationSubjectId'],
                $subject['subject']
            ));
        }

        return $collection;
    }

    /**
     * @return SubscriptionConfirmationBodyCollection
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationsBody()
    {
        $subjects = $this->getresponseApiClient->getSubscriptionConfirmationBody();
        $collection = new SubscriptionConfirmationBodyCollection();

        foreach ($subjects as $subject) {
            $collection->add(new SubscriptionConfirmationBody(
                $subject['subscriptionConfirmationBodyId'],
                $subject['name'],
                $subject['contentPlain']
            ));
        }

        return $collection;
    }

    /**
     * @return ContactListCollection
     * @throws GetresponseApiException
     */
    public function getAllContactLists()
    {
        $campaigns = $this->getresponseApiClient->getContactList();
        $collection = new ContactListCollection();

        foreach ($campaigns as $field) {
            $collection->add(new ContactList(
                $field['campaignId'],
                $field['name']
            ));
        }

        return $collection;
    }

    /**
     * @param null|string $campaignId
     * @return AutorespondersCollection
     * @throws GetresponseApiException
     */
    public function getAutoresponders($campaignId = null)
    {
        $autoresponders = $this->getresponseApiClient->getAutoresponders($campaignId);
        $collection = new AutorespondersCollection();

        foreach ($autoresponders as $field) {
            $collection->add(new Autoresponder(
                $field['autoresponderId'],
                $field['name'],
                $field['campaignId'],
                $field['subject'],
                $field['status'],
                $field['triggerSettings']['dayOfCycle']
            ));
        }

        return $collection;
    }

    /**
     * @param AddContactListCommand $addContactListCommand
     * @return array
     * @throws GetresponseApiException
     */
    public function createContactList(AddContactListCommand $addContactListCommand)
    {
        return $this->getresponseApiClient->createContactList([
            'name' => $addContactListCommand->getContactListName(),
            'confirmation' => [
                'fromField' => ['fromFieldId' => $addContactListCommand->getFromFieldId()],
                'replyTo' => ['fromFieldId' => $addContactListCommand->getReplyToId()],
                'subscriptionConfirmationBodyId' => $addContactListCommand->getSubscriptionConfirmationBodyId(),
                'subscriptionConfirmationSubjectId' => $addContactListCommand->getSubscriptionConfirmationSubjectId()
            ],
            'languageCode' => $addContactListCommand->getLanguageCode()
        ]);
    }

}