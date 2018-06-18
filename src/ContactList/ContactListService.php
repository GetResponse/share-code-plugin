<?php
namespace GrShareCode\ContactList;

use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBody;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBodyCollection;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubject;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubjectCollection;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class ContactListService
 * @package GrShareCode\ContactList
 */
class ContactListService
{
    const PER_PAGE = 100;

    /** @var GetresponseApi */
    private $getresponseApi;

    /**
     * @param GetresponseApi $getresponseApi
     */
    public function __construct(GetresponseApi $getresponseApi)
    {
        $this->getresponseApi = $getresponseApi;
    }

    /**
     * @return FromFieldsCollection
     * @throws GetresponseApiException
     */
    public function getFromFields()
    {
        $fromFields[] = $this->getresponseApi->getFromFields(1, self::PER_PAGE);
        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $fromFields[] = $this->getresponseApi->getFromFields($page, self::PER_PAGE);
        }

        $fromFieldsList = call_user_func_array('array_merge', $fromFields);

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
        $subjects = $this->getresponseApi->getSubscriptionConfirmationSubject();

        $collection = new SubscriptionConfirmationSubjectCollection();

        foreach ($subjects as $subject) {
            $collection->add(new SubscriptionConfirmationSubject(
                $subject['id'],
                $subject['name']
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
        $subjects = $this->getresponseApi->getSubscriptionConfirmationBody();

        $collection = new SubscriptionConfirmationBodyCollection();

        foreach ($subjects as $subject) {
            $collection->add(new SubscriptionConfirmationBody(
                $subject['id'],
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
        $campaigns = $this->getresponseApi->getContactList(1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $campaigns = array_merge($campaigns,  $this->getresponseApi->getContactList($page, self::PER_PAGE));
        }

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
     * @return AutorespondersCollection
     * @throws GetresponseApiException
     */
    public function getAutoresponders()
    {
        $collection = new AutorespondersCollection();

        $autoresponders = $this->getresponseApi->getAutoresponders(array(), 1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $autoresponders = array_merge($autoresponders,  $this->getresponseApi->getAutoresponders(array(), $page, self::PER_PAGE));
        }

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
     * @param string $campaignId
     * @return AutorespondersCollection
     * @throws GetresponseApiException
     */
    public function getCampaignAutoresponders($campaignId)
    {
        $collection = new AutorespondersCollection();

        $autoresponders = $this->getresponseApi->getAutoresponders(['campaignId' => $campaignId], 1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $autoresponders = array_merge($autoresponders,  $this->getresponseApi->getAutoresponders(['campaignId' => $campaignId], $page, self::PER_PAGE));
        }

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


}