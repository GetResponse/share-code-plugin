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

        /** @var ContactCustomField $customField */
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

            $addContactCommand->addCustomField(new ContactCustomField(
                $origin['customFieldId'],
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
     * @param string $contactId
     * @return ContactCustomFieldsCollection
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function getContactCustomFields($contactId)
    {
        $response = $this->getresponseApi->getContactById($contactId);

        if (empty($response)) {
            throw new ContactNotFoundException(sprintf('Contact with Id %s not found.', $contactId));
        }

        $contactCustomFieldCollection = new ContactCustomFieldsCollection();

        foreach ($response['customFieldValues'] as $customField) {
            $contactCustomFieldCollection->add(
                new ContactCustomField($customField['customFieldId'], $customField['value'][0])
            );
        }

        return $contactCustomFieldCollection;
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    public function createContact(AddContactCommand $addContactCommand)
    {
        $params = $this->prepareParams($addContactCommand, $addContactCommand->getCustomFieldsCollection());
        $this->getresponseApi->createContact($params);
    }

    /**
     * @param string $contactId
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     * @throws ContactNotFoundException
     */
    private function updateContact($contactId, AddContactCommand $addContactCommand)
    {
        $grCustomFields = $this->getContactCustomFields($contactId);
        $newCustomFields = $addContactCommand->getCustomFieldsCollection();

        $customFieldsMerger = new ContactCustomFieldBuilder($grCustomFields, $newCustomFields);
        $customFieldCollection = $customFieldsMerger->getMergedCustomFieldsCollection();

        $params = $this->prepareParams($addContactCommand, $customFieldCollection);
        $this->getresponseApi->updateContact($contactId, $params);
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @param ContactCustomFieldsCollection $customFieldCollection
     * @return array
     */
    private function prepareParams(AddContactCommand $addContactCommand, ContactCustomFieldsCollection $customFieldCollection)
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

        /** @var ContactCustomField $customField */
        foreach ($customFieldCollection as $customField) {
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
