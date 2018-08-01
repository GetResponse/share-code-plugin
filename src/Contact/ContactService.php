<?php
namespace GrShareCode\Contact;

use GrShareCode\Export\ExportContactCommand;
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
    public function upsertContact(AddContactCommand $addContactCommand)
    {
        try {
            $contact = $this->getContactByEmail($addContactCommand->getEmail(), $addContactCommand->getContactListId());
            $this->updateContact($contact->getContactId(), $addContactCommand);
        } catch (ContactNotFoundException $e) {

            $origin = $this->getresponseApi->getCustomFieldByName('origin');

            if (empty($origin)) {
                $origin = $this->getresponseApi->createCustomField([
                    'name' => 'origin',
                    'type' => 'text',
                    'hidden' => false,
                    'values' => [$addContactCommand->getOriginValue()]
                ]);
            }

            $addContactCommand->addCustomField(new CustomField(
                $origin['customFieldId'],
                $origin['name'],
                $addContactCommand->getOriginValue()
            ));
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
        $params = $this->prepareParams($addContactCommand);
        $this->getresponseApi->createContact($params);
    }

    /**
     * @param string $contactId
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    private function updateContact($contactId, AddContactCommand $addContactCommand)
    {
        $params = $this->prepareParams($addContactCommand);
        $this->getresponseApi->updateContact($contactId, $params);
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

    /**
     * @param AddContactCommand $addContactCommand
     * @return array
     */
    private function prepareParams(AddContactCommand $addContactCommand)
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

        return $params;
    }

    /**
     * @param string $email
     * @param string $originCustomName
     * @throws GetresponseApiException
     */
    public function unsubscribe($email, $originCustomName)
    {
        $contacts = $this->getresponseApi->searchContacts($email);

        foreach ($contacts as $contact) {

            if (!empty($originCustomName) && $contact['origin'] === $originCustomName) {
                $this->getresponseApi->deleteContact($contact['contactId']);
            }
        }
    }
}
