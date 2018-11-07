<?php
namespace GrShareCode\Contact;

/**
 * Class ContactFactory
 * @package GrShareCode\Contact
 */
class ContactFactory
{
    /** @var ContactCustomFieldCollectionFactory */
    private $contactCustomFieldCollectionFactory;

    /**
     * @param ContactCustomFieldCollectionFactory $contactCustomFieldCollectionFactory
     */
    public function __construct(ContactCustomFieldCollectionFactory $contactCustomFieldCollectionFactory)
    {
        $this->contactCustomFieldCollectionFactory = $contactCustomFieldCollectionFactory;
    }

    /**
     * @param array $response
     * @return Contact
     */
    public function createContactFromResponse(array $response)
    {
        return new Contact(
            $response['contactId'],
            $response['name'],
            $response['email'],
            $this->contactCustomFieldCollectionFactory->fromApiResponse($response)
        );
    }
}