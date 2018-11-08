<?php
namespace GrShareCode\Tests\Integration\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\ContactCustomFieldCollectionFactory;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactFactory;
use GrShareCode\Contact\ContactPayloadFactory;
use GrShareCode\Contact\ContactService;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Tests\Integration\BaseCaseTest;

/**
 * Class ContactTest
 * @package GrShareCode\Tests\Integration\Contact
 */
class ContactTest extends BaseCaseTest
{
    /** @var GetresponseApiClient */
    private $getResponseApiClient;
    /** @var string */
    private $email;
    /** @var CustomFieldService */
    private $customFieldsService;
    /** @var ContactService */
    private $contactService;
    /** @var string */
    private $originCustomName = 'origin2';

    public function setUp()
    {
        $this->getResponseApiClient = $this->getApiClient();
        $this->customFieldsService = new CustomFieldService($this->getResponseApiClient);

        $this->contactService = new ContactService(
            $this->getResponseApiClient,
            new ContactPayloadFactory(),
            new ContactFactory(new ContactCustomFieldCollectionFactory()),
            $this->customFieldsService,
            $this->dbRepositoryMock,
            'originValue'
        );

        $reflectionClass = new \ReflectionClass(ContactService::class);
        $originCustomNameProperty = $reflectionClass->getProperty('originCustomName');
        $originCustomNameProperty->setAccessible(true);
        $originCustomNameProperty->setValue($this->contactService, $this->originCustomName);

        $this->email = 'tester' . md5(time()) . '@getresponse.com';
    }

    public function tearDown()
    {
        $this->customFieldsService->deleteCustomFieldById(
            $this->customFieldsService->getCustomFieldByName($this->originCustomName)['customFieldId']
        );
    }

    /**
     * @test
     */
    public function shouldAddContact()
    {
        $customFields = new ContactCustomFieldsCollection();

        $this->dbRepositoryMock
            ->expects(self::exactly(2))
            ->method('getOriginCustomFieldId')
            ->willReturnOnConsecutiveCalls('aabbcc', '');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('clearOriginCustomField');

        $this->dbRepositoryMock
            ->expects(self::exactly(2))
            ->method('setOriginCustomFieldId');

        $addContactCommand = new AddContactCommand(
            $this->email,
            'tester',
            $this->getConfig()['contactListId'],
            null,
            $customFields
        );

        $this->contactService->addContact($addContactCommand);
    }
}