<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\Command\GetContactCommand;
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

    /** @var ContactPayloadFactory */
    private $contactPayloadFactory;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param ContactPayloadFactory $contactPayloadFactory
     */
    public function __construct(GetresponseApiClient $getresponseApiClient, ContactPayloadFactory $contactPayloadFactory)
    {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->contactPayloadFactory = $contactPayloadFactory;
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    public function addContact(AddContactCommand $addContactCommand)
    {
        try {
            $getContactCommand = GetContactCommand::createWithEmailAndListId(
                $addContactCommand->getEmail(),
                $addContactCommand->getContactListId()
            );

            $contact = $this->getContact($getContactCommand);
            $this->updateContact($contact, $addContactCommand);
        } catch (ContactNotFoundException $e) {
            $this->createContact($addContactCommand);
        }
    }

    /**
     * @param GetContactCommand $getContactCommand
     * @param bool $withCustomFields
     * @return Contact
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function getContact(GetContactCommand $getContactCommand, $withCustomFields = false)
    {
        if (null !== $getContactCommand->getId()) {
            $response = $this->getresponseApiClient->getContactById(
                $getContactCommand->getId(),
                $withCustomFields
            );
        } else {
            $response = $this->getresponseApiClient->getContactByEmailAndListId(
                $getContactCommand->getId(),
                $getContactCommand->getListId(),
                $withCustomFields
            );
        }

        if (empty($response)) {
            throw ContactNotFoundException::createFromGetContactCommand($getContactCommand);
        }

        return new Contact(
            $response['contactId'],
            $response['name'],
            $response['email'],
            (new ContactCustomFieldCollectionFactory)->fromApiResponse($response)
        );
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    private function createContact(AddContactCommand $addContactCommand)
    {
        //$origin = $this->getresponseApiClient->getCustomFieldByName('origin');

        /*if (empty($origin)) {
            $origin = $this->getresponseApiClient->createCustomField([
                'name' => 'origin',
                'type' => 'text',
                'hidden' => false,
                'values' => [$addContactCommand->getOriginValue()]
            ]);
        }*/

        $addContactCommand->addCustomField($addContactCommand->getOriginCustomField());

        $this->getresponseApiClient->createContact(
            $this->contactPayloadFactory->createFromAddContactCommand($addContactCommand)
        );
    }

    /**
     * @param Contact $contact
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    private function updateContact(Contact $contact, AddContactCommand $addContactCommand)
    {
        $addContactCommand->setCustomFieldsCollection(
            (new ContactCustomFieldBuilder(
                $contact->getContactCustomFieldCollection(),
                $addContactCommand->getCustomFieldsCollection()
            ))
                ->getMergedCustomFieldsCollection()
        );

        $this->getresponseApiClient->updateContact(
            $contact->getContactId(),
            $this->contactPayloadFactory->createFromAddContactCommand($addContactCommand)
        );
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
