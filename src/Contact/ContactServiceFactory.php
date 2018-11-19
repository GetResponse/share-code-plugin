<?php
namespace GrShareCode\Contact;

use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldCollectionFactory;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\DbRepositoryInterface;

/**
 * Class ContactServiceFactory
 * @package GrShareCode\Contact
 */
class ContactServiceFactory
{
    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @param $originValue
     * @return ContactService
     */
    public function create(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository, $originValue)
    {
        return new ContactService(
            $getresponseApiClient,
            new ContactPayloadFactory(),
            new ContactFactory(new ContactCustomFieldCollectionFactory()),
            new CustomFieldService($getresponseApiClient),
            $dbRepository,
            $originValue
        );
    }
}