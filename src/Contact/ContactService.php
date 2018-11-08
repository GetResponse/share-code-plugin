<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\Command\FindContactCommand;
use GrShareCode\Contact\Command\GetContactCommand;
use GrShareCode\Contact\Command\UnsubscribeContactsCommand;
use GrShareCode\CustomField\Command\CreateCustomFieldCommand;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\CustomFieldNotFoundException;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class ContactService
 * @package GrShareCode\Contact
 */
class ContactService
{
    const PER_PAGE = 100;
    private $originCustomName = 'origin';

    /** @var GetresponseApiClient */
    private $getresponseApiClient;
    /** @var ContactPayloadFactory */
    private $contactPayloadFactory;
    /** @var ContactFactory */
    private $contactFactory;
    /** @var string */
    private $originValue;
    /** @var CustomFieldService */
    private $customFieldService;
    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param ContactPayloadFactory $contactPayloadFactory
     * @param ContactFactory $contactFactory
     * @param CustomFieldService $customFieldService
     * @param DbRepositoryInterface $dbRepository
     * @param string $originValue
     */
    public function __construct(
        GetresponseApiClient $getresponseApiClient,
        ContactPayloadFactory $contactPayloadFactory,
        ContactFactory $contactFactory,
        CustomFieldService $customFieldService,
        DbRepositoryInterface $dbRepository,
        $originValue
    ) {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->contactPayloadFactory = $contactPayloadFactory;
        $this->contactFactory = $contactFactory;
        $this->originValue = $originValue;
        $this->customFieldService = $customFieldService;
        $this->dbRepository = $dbRepository;
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
        $originCustomField = new ContactCustomField($this->getOriginContactCustomFieldId(), [$this->originValue]);
        $addContactCommand->getContactCustomFieldsCollection()->add($originCustomField);

        try {
            $this->getresponseApiClient->createContact(
                $this->contactPayloadFactory->createFromAddContactCommand($addContactCommand)
            );
        } catch (CustomFieldNotFoundException $exception) {

            if ($exception->getCustomFieldId() == $originCustomField->getId()) {

                $addContactCommand->getContactCustomFieldsCollection()->remove($originCustomField);
                $addContactCommand->getContactCustomFieldsCollection()->add(
                    $this->recreateOriginContactCustomField()
                );

                $this->getresponseApiClient->createContact(
                    $this->contactPayloadFactory->createFromAddContactCommand($addContactCommand)
                );
            }
        }
    }

    /**
     * @return ContactCustomField
     * @throws GetresponseApiException
     */
    private function recreateOriginContactCustomField()
    {
        $this->dbRepository->clearOriginCustomField();
        return new ContactCustomField($this->getOriginContactCustomFieldId(), [$this->originValue]);
    }

    /**
     * @param Contact $contact
     * @param AddContactCommand $addContactCommand
     * @throws GetresponseApiException
     */
    private function updateContact(Contact $contact, AddContactCommand $addContactCommand)
    {
        $contactCustomFieldCollectionBuilder = new ContactCustomFieldBuilder(
            $contact->getContactCustomFieldCollection(),
            $addContactCommand->getContactCustomFieldsCollection()
        );

        $addContactCommand->setCustomFieldsCollection(
            $contactCustomFieldCollectionBuilder->getMergedCustomFieldsCollection()
        );

        $addContactCommand->clearDayOfCycle();

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
        $originContactCustomField = new ContactCustomField($this->getOriginContactCustomFieldId(), [$this->originValue]);
        $rawContacts = $this->getresponseApiClient->searchContacts($unsubscribeCommand->getEmail(), true);

        foreach ($rawContacts as $rowContact) {
            $contact = $this->contactFactory->createContactFromResponse($rowContact);
            if ($contact->hasOriginCustomField($originContactCustomField)) {
                $this->getresponseApiClient->deleteContact($contact->getContactId());
            }
        }
    }

    /**
     * @throws GetresponseApiException
     * @return string
     */
    private function getOriginContactCustomFieldId()
    {
        $originCustomFieldId = $this->dbRepository->getOriginCustomFieldId();

        if (!empty($originCustomFieldId)) {
            return $originCustomFieldId;
        }

        $originCustomField = $this->customFieldService->getCustomFieldByName($this->originCustomName);

        if (empty($originCustomField)) {
            $originCustomField = $this->customFieldService->createCustomField(
                new CreateCustomFieldCommand(
                    $this->originCustomName,
                    [$this->originValue]
                )
            );
        }

        $originCustomFieldId = $originCustomField['customFieldId'];
        $this->dbRepository->setOriginCustomFieldId($originCustomFieldId);

        return $originCustomFieldId;
    }
}
