<?php
namespace GrShareCode\Contact;

use GrShareCode\Export\ExportContactCommand;
use GrShareCode\Export\Settings\ExportSettings;
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
     * @param ExportContactCommand $exportContactCommand
     * @param string $contactId
     * @throws GetresponseApiException
     */
    public function updateContactOnExport(ExportContactCommand $exportContactCommand, $contactId) {

        $exportSettings = $exportContactCommand->getExportSettings();

        if (!$exportSettings->isUpdateContactEnabled()) {
            return;
        }

        $params = [
            'name' => $exportContactCommand->getName(),
            'dayOfCycle' => $exportSettings->getDayOfCycle(),
            'campaign' => [
                'campaignId' => $exportSettings->getContactListId(),
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
    public function addContact(AddContactCommand $addContactCommand)
    {
        try {
            $this->getContactByEmail($addContactCommand->getEmail(), $addContactCommand->getContactListId());
        } catch (ContactNotFoundException $e) {
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
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    public function createContact(AddContactCommand $addContactCommand)
    {
        $params = [
            'name' => $addContactCommand->getName(),
            'email' => $addContactCommand->getEmail(),
            'campaign' => [
                'campaignId' => $addContactCommand->getContactListId(),
            ]
        ];

        if (null !== $addContactCommand->getDayOfCycle()) {
            $params['dayOfCycle'] = $addContactCommand->getDayOfCycle();
        }

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
