<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
use GrShareCode\Contact\CustomField;
use GrShareCode\Contact\CustomFieldsCollection;
use GrShareCode\GetresponseApi;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class ContactServiceTest extends TestCase
{

    /** @var GetresponseApi|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiMock;

    public function setUp()
    {
        $this->getResponseApiMock = $this->getMockBuilder(GetresponseApi::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldReturnContact()
    {
        $email = 'adam.kowalski@getresponse.com';
        $contactListId = 'grListId';

        $contact = new Contact('grContactId', 'Adam Kowalski', $email);

        $this->getResponseApiMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with($email, $contactListId)
            ->willReturn([
                'contactId' => 'grContactId',
                'name' => 'Adam Kowalski',
                'email' => $email
            ]);

        $contactService = new ContactService($this->getResponseApiMock);
        $this->assertEquals($contact, $contactService->getContactByEmail($email, $contactListId));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenClientNotExists()
    {
        $email = 'adam.kowalski@getresponse.com';
        $contactListId = 'grListId';

        $this->getResponseApiMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with($email, $contactListId)
            ->willReturn([]);

        $this->expectException(ContactNotFoundException::class);

        $contactService = new ContactService($this->getResponseApiMock);
        $contactService->getContactByEmail($email, $contactListId);
    }

    /**
     * @test
     */
    public function shouldReturnAllCustomFields()
    {
        $this->getResponseApiMock
            ->expects($this->exactly(3))
            ->method('getCustomFields')
            ->willReturnOnConsecutiveCalls(
                [['name' => 'customFieldName1', 'customFieldId' => 'grCustomFieldId1']],
                [['name' => 'customFieldName2', 'customFieldId' => 'grCustomFieldId2']],
                [['name' => 'customFieldName3', 'customFieldId' => 'grCustomFieldId3']]
            );

        $this->getResponseApiMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['TotalPages' => '3']);


        $customFieldCollection = new CustomFieldsCollection();
        $customFieldCollection->add(new CustomField('grCustomFieldId1', 'customFieldName1'));
        $customFieldCollection->add(new CustomField('grCustomFieldId2', 'customFieldName2'));
        $customFieldCollection->add(new CustomField('grCustomFieldId3', 'customFieldName3'));

        $contactService = new ContactService($this->getResponseApiMock);
        $this->assertEquals($customFieldCollection, $contactService->getAllCustomFields());
    }

    /**
     * @test
     */
    public function shouldCreateContact()
    {
        $params = [
            'name' => 'Adam Kowalski',
            'email' => 'adam.kowalski@getresponse.com',
            'campaign' => [
                'campaignId' => 'contactListId'
            ],
            'dayOfCycle' => 3,
            'customFieldValues' => [
                ['customFieldId' => 'id_1', 'value' => ['value_1']],
                ['customFieldId' => 'id_2', 'value' => ['value_2']]
            ]
        ];
        $this->getResponseApiMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

        $addContactCommand = Generator::createAddContactCommand();

        $contactService = new ContactService($this->getResponseApiMock);
        $contactService->createContact($addContactCommand);

    }
}
