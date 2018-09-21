<?php
namespace GrShareCode\Tests\Integration;

use GrShareCode\Contact\AddContactCommand;
use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactService;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;

/**
 * Class Contact
 * @package GrShareCode\Tests\Integration
 */
class ContactTest extends BaseCaseTest
{
    /** @var GetresponseApiClient */
    private $getResponseApiClient;

    /** @var string */
    private $customFieldId1;

    /** @var string */
    private $customFieldId2;

    /** @var string */
    private $customFieldId3;

    /** @var string */
    private $customFieldId4;

    /** @var string */
    private $customFieldId5;

    /** @var string */
    private $customFieldId6;

    /** @var string */
    private $email;

    /** @var CustomFieldService */
    private $customFieldsService;

    /** @var ContactService */
    private $contactService;

    public function setUp()
    {
        $this->getResponseApiClient = $this->getApiClient();

        $this->contactService = new ContactService($this->getResponseApiClient);

        $this->customFieldsService = new CustomFieldService($this->getResponseApiClient);
        $this->customFieldId1 = $this->customFieldsService->createCustomField('share_code_test1', 'shareCodeTest1')['customFieldId'];
        $this->customFieldId2 = $this->customFieldsService->createCustomField('share_code_test2', 'shareCodeTest2')['customFieldId'];
        $this->customFieldId3 = $this->customFieldsService->createCustomField('share_code_test3', 'shareCodeTest3')['customFieldId'];
        $this->customFieldId4 = $this->customFieldsService->createCustomField('share_code_test4', 'shareCodeTest4')['customFieldId'];
        $this->customFieldId5 = $this->customFieldsService->createCustomField('share_code_test5', 'shareCodeTest5')['customFieldId'];
        $this->customFieldId6 = $this->customFieldsService->createCustomField('share_code_test6', 'shareCodeTest6')['customFieldId'];

        $this->email = 'tester' . md5(time()) . '@getresponse.com';
    }

    public function tearDown()
    {
        $this->customFieldsService->deleteCustomFieldById($this->customFieldId1);
        $this->customFieldsService->deleteCustomFieldById($this->customFieldId2);
        $this->customFieldsService->deleteCustomFieldById($this->customFieldId3);
        $this->customFieldsService->deleteCustomFieldById($this->customFieldId4);
        $this->customFieldsService->deleteCustomFieldById($this->customFieldId5);
        $this->customFieldsService->deleteCustomFieldById($this->customFieldId6);

        $this->contactService->unsubscribe($this->email, 'shareCode');
    }

    /**
     * @test
     */
    public function shouldAppendContactCustomFields()
    {
        $this->addNewContactToGetResponse();
        $this->updateExistingContactCustomFields();

        $originCustomFieldId = $this->customFieldsService->getCustomFieldByName('origin')['customFieldId'];
        $expectedContactCustomFields = new ContactCustomFieldsCollection();
        $expectedContactCustomFields->add(new ContactCustomField($originCustomFieldId, 'shareCode'));
        $expectedContactCustomFields->add(new ContactCustomField($this->customFieldId1, 'value1'));
        $expectedContactCustomFields->add(new ContactCustomField($this->customFieldId3, 'value3'));
        $expectedContactCustomFields->add(new ContactCustomField($this->customFieldId2, 'value7'));
        $expectedContactCustomFields->add(new ContactCustomField($this->customFieldId4, 'value4'));
        $expectedContactCustomFields->add(new ContactCustomField($this->customFieldId5, 'value5'));
        $expectedContactCustomFields->add(new ContactCustomField($this->customFieldId6, 'value6'));

        $contact = $this->contactService->getContactByEmail($this->email, $this->getConfig()['contactListId']);
        $actualContactCustomFields = $this->contactService->getContactCustomFields($contact->getContactId());

        $this->assertEquals($expectedContactCustomFields, $actualContactCustomFields);
    }

    /**
     * @return array
     * @throws GetresponseApiException
     */
    private function addNewContactToGetResponse()
    {
        $customFields = new ContactCustomFieldsCollection();
        $customFields->add(new ContactCustomField($this->customFieldId1, 'value1'));
        $customFields->add(new ContactCustomField($this->customFieldId2, 'value2'));
        $customFields->add(new ContactCustomField($this->customFieldId3, 'value3'));

        $addContactCommand = new AddContactCommand($this->email, 'tester', $this->getConfig()['contactListId'], null, $customFields, 'shareCode');

        $this->contactService->upsertContact($addContactCommand);
    }

    /**
     * @throws GetresponseApiException
     */
    private function updateExistingContactCustomFields()
    {
        $customFields = new ContactCustomFieldsCollection();
        $customFields->add(new ContactCustomField($this->customFieldId4, 'value4'));
        $customFields->add(new ContactCustomField($this->customFieldId2, 'value7'));
        $customFields->add(new ContactCustomField($this->customFieldId5, 'value5'));
        $customFields->add(new ContactCustomField($this->customFieldId6, 'value6'));

        $addContactCommand = new AddContactCommand($this->email, 'tester', $this->getConfig()['contactListId'], null, $customFields, 'shareCode');

        $contactService = new ContactService($this->getResponseApiClient);
        $contactService->upsertContact($addContactCommand);
    }
}