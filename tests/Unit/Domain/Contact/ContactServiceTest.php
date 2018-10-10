<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\AddContactCommand;
use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Tests\Generator;
use GrShareCode\Tests\Unit\BaseTestCase;
use PHPUnit\Framework\TestCase;

/**
 * Class ContactServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
    }

    /**
     * @test
     */
    public function shouldGetContactByEmail()
    {
        $email = 'adam.kowalski@getresponse.com';
        $contactListId = 'grListId';

        $contact = new Contact('grContactId', 'Adam Kowalski', $email, new ContactCustomFieldsCollection());

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with($email, $contactListId)
            ->willReturn([
                'contactId' => 'grContactId',
                'name' => 'Adam Kowalski',
                'email' => $email
            ]);

        $contactService = new ContactService($this->getResponseApiClientMock);
        $this->assertEquals($contact, $contactService->getContactByEmail($email, $contactListId));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenClientNotExists()
    {
        $email = 'adam.kowalski@getresponse.com';
        $contactListId = 'grListId';

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with($email, $contactListId)
            ->willReturn([]);

        $this->expectException(ContactNotFoundException::class);

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->getContactByEmail($email, $contactListId);
    }

    /**
     * @test
     * @dataProvider validAddContactProvider
     * @param $addContactCommand
     * @param $params
     * @throws \GrShareCode\GetresponseApiException
     */
    public function shouldCreateContact($addContactCommand, $params)
    {
        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->createContact($addContactCommand);
    }

    /**
     * @return array
     */
    public function validAddContactProvider()
    {
        $addContactCommand = Generator::createAddContactCommand();
        $expectedParams = [
            'name' => 'Adam Kowalski',
            'email' => 'adam.kowalski@getresponse.com',
            'campaign' => [
                'campaignId' => 'contactListId'
            ],
            'dayOfCycle' => 3,
            'customFieldValues' => [
                ['customFieldId' => 'id_1', 'value' => ['value_1']],
                ['customFieldId' => 'id_2', 'value' => ['value_2']],
                ['customFieldId' => '', 'value' => ['origin']]
            ]
        ];

        $addContactCommand1 = Generator::createAddContactCommand('');
        $expectedParams1 = $expectedParams;
        unset($expectedParams1['name']);

        $addContactCommand2 = Generator::createAddContactCommand(null);
        $expectedParams2 = $expectedParams;
        unset($expectedParams2['name']);

        return [
            [
                'add_contact_command' => $addContactCommand,
                'expected_params' => $expectedParams,
            ],
            [
                'add_contact_command' => $addContactCommand1,
                'expected_params' => $expectedParams1,
            ],
            [
                'add_contact_command' => $addContactCommand2,
                'expected_params' => $expectedParams2
            ]
        ];
    }

    /**
     * @test
     */
    public function shouldCreateContactOnUpsertAndCreateCustomField()
    {
        $email = 'adam.kowalski@getresponse.com';
        $listId = 'X3d9';
        $originCustomFieldId = '4jdk';
        $originCustomFieldValue = 'magento2';

        $params = [
            'name' => 'Adam Kowalski',
            'email' => $email,
            'campaign' => [
                'campaignId' => $listId
            ],
            'dayOfCycle' => 3,
            'customFieldValues' => [
                ['customFieldId' => 'id_1', 'value' => ['value_1']],
                ['customFieldId' => 'id_2', 'value' => ['value_2']],
                ['customFieldId' => $originCustomFieldId, 'value' => [$originCustomFieldValue]]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getContactWithCustomFieldsByEmail')
            ->with($email, $listId)->willReturn(null);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getCustomFieldByName')
            ->with('origin')->willReturn(null);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createCustomField')
            ->willReturn(['customFieldId' => $originCustomFieldId]);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

        $customFieldCollection = new ContactCustomFieldsCollection();
        $customFieldCollection->add(new ContactCustomField('id_1',  'value_1'));
        $customFieldCollection->add(new ContactCustomField('id_2', 'value_2'));

        $addContactCommand = new AddContactCommand(
            $email,
            'Adam Kowalski',
            $listId,
            3,
            $customFieldCollection,
            $originCustomFieldValue
        );

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->upsertContact($addContactCommand);
    }

    /**
     * @test
     */
    public function shouldCreateContactOnUpsertAndNotCreateCustomField()
    {
        $email = 'adam.kowalski@getresponse.com';
        $listId = 'X3d9';
        $originCustomFieldId = '4jdk';
        $originCustomFieldValue = 'magento2';

        $params = [
            'name' => 'Adam Kowalski',
            'email' => $email,
            'campaign' => [
                'campaignId' => $listId
            ],
            'dayOfCycle' => 3,
            'customFieldValues' => [
                ['customFieldId' => 'id_1', 'value' => ['value_1']],
                ['customFieldId' => 'id_2', 'value' => ['value_2']],
                ['customFieldId' => $originCustomFieldId, 'value' => [$originCustomFieldValue]]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getContactWithCustomFieldsByEmail')
            ->with($email, $listId)->willReturn(null);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getCustomFieldByName')
            ->with('origin')->willReturn(['customFieldId' => $originCustomFieldId]);

        $this->getResponseApiClientMock
            ->expects($this->never())
            ->method('createCustomField');

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

        $customFieldCollection = new ContactCustomFieldsCollection();
        $customFieldCollection->add(new ContactCustomField('id_1',  'value_1'));
        $customFieldCollection->add(new ContactCustomField('id_2', 'value_2'));

        $addContactCommand = new AddContactCommand(
            $email,
            'Adam Kowalski',
            $listId,
            3,
            $customFieldCollection,
            $originCustomFieldValue
        );

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->upsertContact($addContactCommand);
    }

    /**
     * @test
     */
    public function shouldCreateContactWithOriginIfCustomFieldNotExists()
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
                ['customFieldId' => 'id_2', 'value' => ['value_2']],
                ['customFieldId' => '', 'value' => ['origin']]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getCustomFieldByName')
            ->with('origin')
            ->willReturn(null);

        $addContactCommand = Generator::createAddContactCommand();

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->createContact($addContactCommand);
    }

    /**
     * @test
     */
    public function shouldCreateContactWithOriginIfCustomFieldExists()
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
                ['customFieldId' => 'id_2', 'value' => ['value_2']],
                ['customFieldId' => 'cx4R', 'value' => ['origin']]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getCustomFieldByName')
            ->with('origin')
            ->willReturn(['customFieldId' => 'cx4R']);

        $addContactCommand = Generator::createAddContactCommand();

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->createContact($addContactCommand);
    }

    /**
     * @test
     */
    public function shouldUnsubscribeContact()
    {
        $email = 'test@test.com';
        $origin = 'shopify';

        $this->getResponseApiClientMock->expects(self::once())->method('searchContacts')->with($email)->willReturn([
            [
                'contactId' => 'xyd',
                'origin' => $origin
            ]
        ]);
        $this->getResponseApiClientMock->expects(self::once())->method('deleteContact');

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->unsubscribe($email, $origin);
    }

    /**
     * @test
     * @dataProvider invalidSubscriberParams
     * @param string $email
     * @param string $origin
     * @param string $invalidOrigin
     */
    public function shouldNotUnsubscribeContact($email, $origin, $invalidOrigin)
    {
        $this->getResponseApiClientMock->expects(self::once())->method('searchContacts')->with($email)->willReturn([
            [
                'contactId' => 'xyd',
                'origin' => $origin
            ]
        ]);
        $this->getResponseApiClientMock->expects(self::never())->method('deleteContact');

        $contactService = new ContactService($this->getResponseApiClientMock);
        $contactService->unsubscribe($email, $invalidOrigin);
    }

    /**
     * @return array
     */
    public function invalidSubscriberParams()
    {
        return [
            ['test@test.com', 'shopify', 'woocommerce'],
            ['test@test.com', 'shopify', '']
        ];
    }
}
