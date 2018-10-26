<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\Command\FindContactCommand;
use GrShareCode\Contact\Command\GetContactCommand;
use GrShareCode\Contact\Command\UnsubscribeContactsCommand;
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
    /** @var ContactCustomField */
    private $originCustomField;
    /** @var ContactFactory */
    private $contactFactory;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param ContactPayloadFactory $contactPayloadFactory
     * @param ContactFactory $contactFactory
     * @param ContactCustomField $originCustomField
     */
    public function __construct(
        GetresponseApiClient $getresponseApiClient,
        ContactPayloadFactory $contactPayloadFactory,
        ContactFactory $contactFactory,
        ContactCustomField $originCustomField
    ) {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->contactPayloadFactory = $contactPayloadFactory;
        $this->originCustomField = $originCustomField;
        $this->contactFactory = $contactFactory;
    }

    /**
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    public function addContact(AddContactCommand $addContactCommand)
    {
        $findContactCommand = new FindContactCommand(
            $addContactCommand->getEmail(),
            $addContactCommand->getContactListId(),
            $addContactCommand->updateIfAlreadyExists()
        );

        $contact = $this->findContact($findContactCommand);

        if (false === $contact) {
            $this->createContact($addContactCommand);
        } else if ($addContactCommand->updateIfAlreadyExists()) {
            $this->updateContact(
                $contact,
                $addContactCommand
            );
        }
    }

    /**
     * @param GetContactCommand $getContactCommand
     * @return Contact
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function getContact(GetContactCommand $getContactCommand)
    {
        $response = $this->getresponseApiClient->getContactById(
            $getContactCommand->getId(),
            $getContactCommand->withCustoms()
        );

        if (empty($response)) {
            throw ContactNotFoundException::createFromGetContactCommand($getContactCommand);
        }

        return $this->contactFactory->createContactFromResponse($response);
    }

    /**
     * @param FindContactCommand $findContactCommand
     * @return Contact|false
     * @throws GetresponseApiException
     */
    public function findContact(FindContactCommand $findContactCommand)
    {
        $response = $this->getresponseApiClient->findContactByEmailAndListId(
            $findContactCommand->getEmail(),
            $findContactCommand->getListId(),
            $findContactCommand->withCustoms()
        );

        if (empty($response)) {
            return false;
        }

        return $this->contactFactory->createContactFromResponse($response);
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

        $addContactCommand->addCustomField($this->originCustomField);

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
                $addContactCommand->getContactCustomFieldsCollection()
            ))
                ->getMergedCustomFieldsCollection()
        );

        $this->getresponseApiClient->updateContact(
            $contact->getContactId(),
            $this->contactPayloadFactory->createFromAddContactCommand($addContactCommand)
        );
    }

    /**
     * @param UnsubscribeContactsCommand $unsubscribeCommand
     * @throws GetresponseApiException
     */
    public function unsubscribeContacts(UnsubscribeContactsCommand $unsubscribeCommand)
    {
        $rawContacts = $this->getresponseApiClient->searchContacts($unsubscribeCommand->getEmail());

        foreach ($rawContacts as $rowContact) {
            $contact = $this->contactFactory->createContactFromResponse($rowContact);
            if ($contact->hasContactCustomField($this->originCustomField)) {
                $this->getresponseApiClient->deleteContact($contact->getContactId());
            }
        }
    }
}
