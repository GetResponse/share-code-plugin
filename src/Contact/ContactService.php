<?php
namespace GrShareCode\Contact;

use GrShareCode\Export\Config\Config;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;

/**
 * Class ContactService
 * @package GrShareCode\Contact
 */
class ContactService
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
     * @param Config $config
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    public function exportContact(Config $config, ExportContactCommand $exportContactCommand)
    {

        try {
            $contact = $this->getContactByEmail(
                $exportContactCommand->getEmail(),
                $config->getContactListId()
            );

            $this->updateContact($config, $exportContactCommand, $contact->getContactId());

        } catch (ContactNotFoundException $e) {

            $addContactCommand = new AddContactCommand(
                $exportContactCommand->getEmail(),
                $exportContactCommand->getName(),
                $config->getContactListId(),
                $config->getDayOfCycle(),
                $exportContactCommand->getCustomFieldsCollection()
            );
            $this->createContact($addContactCommand);
        }

    }

    /**
     * @param string $email
     * @param string $listId
     * @return Contact
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function getContactByEmail($email, $listId)
    {
        $response = $this->getresponseApi->getContactByEmail($email, $listId);

        if (empty($response)) {
            throw new ContactNotFoundException();
        }

        return new Contact(
            $response['contactId'],
            $response['name'],
            $response['email']
        );
    }

    /**
     * @param Config $config
     * @param ExportContactCommand $exportContactCommand
     * @param string $contactId
     * @throws GetresponseApiException
     */
    private function updateContact(Config $config, ExportContactCommand $exportContactCommand, $contactId)
    {
        if (!$config->isUpdateContactEnabled()) {
            return;
        }

        $params = [
            'name' => $exportContactCommand->getName(),
            'dayOfCycle' => $config->getDayOfCycle(),
            'campaign' => [
                'campaignId' => $config->getContactListId(),
            ]
        ];

        /** @var CustomField $customField */
        foreach ($exportContactCommand->getCustomFieldsCollection() as $customField) {
            $params['customFieldValues'][] = [
                'customFieldId' => $customField->getId(),
                'value' => [$customField->getValue()]
            ];
        }

        $this->getresponseApi->updateContact($contactId, $params);
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    private function createContact(AddContactCommand $addContactCommand)
    {
        $params = [
            'name' => $addContactCommand->getName(),
            'email' => $addContactCommand->getEmail(),
            'dayOfCycle' => $addContactCommand->getDayOfCycle(),
            'campaign' => [
                'campaignId' => $addContactCommand->getContactListId(),
            ]
        ];

        /** @var CustomField $customField */
        foreach ($addContactCommand->getCustomFieldsCollection() as $customField) {
            $params['customFieldValues'][] = [
                'customFieldId' => $customField->getId(),
                'value' => [$customField->getValue()]
            ];
        }

        $this->getresponseApi->createContact($params);
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    public function addContact(AddContactCommand $addContactCommand)
    {
        try {
            $this->getContactByEmail($addContactCommand->getEmail(), $addContactCommand->getContactListId());
        } catch (ContactNotFoundException $e) {
            $this->createContact($addContactCommand);
        }
    }

    /**
     * @return CustomFieldsCollection
     * @throws GetresponseApiException
     */
    public function getAllCustomFields()
    {
        $customFields = $this->getresponseApi->getCustomFields(1, self::PER_PAGE);

        $headers = $this->getresponseApi->getHeaders();

        for ($page = 2; $page <= $headers['TotalPages']; $page++) {
            $customFields = array_merge($customFields, $this->getresponseApi->getCustomFields($page, self::PER_PAGE));
        }

        $collection = new CustomFieldsCollection();

        foreach ($customFields as $field) {
            $collection->add(new CustomField(
                $field['customFieldId'],
                $field['name']
            ));
        }

        return $collection;
    }

}
