<?php
namespace GrShareCode\Contact;

use GrShareCode\Export\ExportContactCommand;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class ContactService
 * @package GrShareCode\Contact
 */
class ContactService
{
    const PER_PAGE = 100;

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
                'value' => $customField->getValues()
            ];
        }
        $this->getresponseApiClient->updateContact($contactId, $params);
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    public function upsertContact(AddContactCommand $addContactCommand)
    {
        try {
            $contact = $this->getContactWithCustomFieldsByEmail($addContactCommand->getEmail(), $addContactCommand->getContactListId());
            $this->updateContact($contact, $addContactCommand);
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
        $response = $this->getresponseApiClient->getContactByEmail($email, $listId);

        if (empty($response)) {
            throw new ContactNotFoundException();
        }

        return new Contact(
            $response['contactId'],
            $response['name'],
            $response['email'],
            new ContactCustomFieldsCollection()
        );
    }

    /**
     * @param string $email
     * @param string $listId
     * @return Contact
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function getContactWithCustomFieldsByEmail($email, $listId)
    {
        $response = $this->getresponseApiClient->getContactWithCustomFieldsByEmail($email, $listId);

        if (empty($response)) {
            throw ContactNotFoundException::withEmailAndContactListId($email, $listId);
        }

        $contactCustomFieldCollection = (new ContactCustomFieldCollectionFactory)->fromApiResponse($response);

        return new Contact(
            $response['contactId'],
            $response['name'],
            $response['email'],
            $contactCustomFieldCollection
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
        $response = $this->getresponseApiClient->getContactById($contactId);

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
        $origin = $this->getresponseApiClient->getCustomFieldByName('origin');

        if (empty($origin)) {
            $origin = $this->getresponseApiClient->createCustomField([
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

        $params = $this->prepareParams($addContactCommand, $addContactCommand->getCustomFieldsCollection());

        $this->getresponseApiClient->createContact($params);
    }

    /**
     * @param Contact $contact
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    private function updateContact(Contact $contact, AddContactCommand $addContactCommand)
    {
        $grCustomFields = $contact->getContactCustomFieldCollection();
        $newCustomFields = $addContactCommand->getCustomFieldsCollection();

        $customFieldsMerger = new ContactCustomFieldBuilder($grCustomFields, $newCustomFields);
        $customFieldCollection = $customFieldsMerger->getMergedCustomFieldsCollection();

        $params = $this->prepareParams($addContactCommand, $customFieldCollection);
        $this->getresponseApiClient->updateContact($contact->getContactId(), $params);
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

        if (empty($params['name'])) {
            unset($params['name']);
        }

        if (!empty($addContactCommand->getDayOfCycle())) {
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
        $contacts = $this->getresponseApiClient->searchContacts($email);

        foreach ($contacts as $contact) {

            if (!empty($originCustomName) && $contact['origin'] === $originCustomName) {
                $this->getresponseApiClient->deleteContact($contact['contactId']);
            }
        }
    }
}
