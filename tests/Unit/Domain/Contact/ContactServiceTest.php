<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\Command\FindContactCommand;
use GrShareCode\Contact\Command\GetContactCommand;
use GrShareCode\Contact\Command\UnsubscribeContactsCommand;
use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactCustomField\ContactCustomField;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldCollectionFactory;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactFactory;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactPayloadFactory;
use GrShareCode\Contact\ContactService;
use GrShareCode\CustomField\Command\CreateCustomFieldCommand;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\Api\Exception\CustomFieldNotFoundException;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ContactServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var ContactPayloadFactory|\PHPUnit_Framework_MockObject_MockObject */
    private $contactPayloadFactoryMock;
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;
    /** @var CustomFieldService|\PHPUnit_Framework_MockObject_MockObject */
    private $customFieldServiceMock;
    /** @var string */
    private $originCustomName;

    /** @var ContactService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->contactPayloadFactoryMock = $this->getMockWithoutConstructing(ContactPayloadFactory::class);
        $this->dbRepositoryMock = $this->getMockWithoutConstructing(DbRepositoryInterface::class);
        $this->customFieldServiceMock = $this->getMockWithoutConstructing(CustomFieldService::class);

        $this->originCustomName = 'originValue';

        $this->sut = new ContactService(
            $this->getResponseApiClientMock,
            $this->contactPayloadFactoryMock,
            new ContactFactory(new ContactCustomFieldCollectionFactory()),
            $this->customFieldServiceMock,
            $this->dbRepositoryMock,
            $this->originCustomName
        );
    }

    /**
     * @test
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function shouldGetContact()
    {
        $id = 'grContactId';
        $name = 'Adam Kowalski';
        $email = 'adam.kowalski@getresponse.com';

        $getContactCommand = new GetContactCommand($id, true);

        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();
        $contactCustomFieldsCollection->add(
            new ContactCustomField('n', ['white', 'black'])
        );

        $contact = new Contact($id, $name, $email, $contactCustomFieldsCollection);

        $response = [
            'contactId' => $id,
            'name' => $name,
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => 'n',
                    'value' => [
                        'white',
                        'black'
                    ]
                ]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getContactById')
            ->with($id, true)
            ->willReturn($response);

        $this->assertEquals(
            $contact,
            $this->sut->getContact($getContactCommand)
        );
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldFindContact()
    {
        $id = 'grContactId';
        $name = 'Adam Kowalski';
        $email = 'adam.kowalski@getresponse.com';
        $contactListId = 'grListId';

        $findContactCommand = new FindContactCommand($email, $contactListId, true);

        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();
        $contactCustomFieldsCollection->add(
            new ContactCustomField('n', ['white'])
        );
        $contact = new Contact($id, $name, $email, $contactCustomFieldsCollection);

        $response = [
            'contactId' => $id,
            'name' => $name,
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => 'n',
                    'value' => [
                        'white'
                    ]
                ]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('findContactByEmailAndListId')
            ->with($email, $contactListId, true)
            ->willReturn($response);

        $this->assertEquals(
            $contact,
            $this->sut->findContact($findContactCommand)
        );
    }

    /**
     * @test
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function shouldThrowExceptionWhenContactDoesntExist()
    {
        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('getContactById')
            ->willReturn([]);

        $this->expectException(ContactNotFoundException::class);
        $this->sut->getContact(new GetContactCommand('id'));
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldAddContactAndCreateCustomField()
    {
        $addContactCommand = new AddContactCommand(
            'example@exmple.com',
            'name',
            'listId',
            null,
            new ContactCustomFieldsCollection()
        );

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getOriginCustomFieldId')
            ->willReturn(null);

        $this->customFieldServiceMock
            ->expects(self::once())
            ->method('getCustomFieldByName')
            ->with('origin')
            ->willReturn(null);

        $this->customFieldServiceMock
            ->expects(self::once())
            ->method('createCustomField')
            ->with(new CreateCustomFieldCommand('origin', [$this->originCustomName]))
            ->willReturn(['customFieldId' => 'oid']);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('setOriginCustomFieldId')
            ->with('oid');

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('findContactByEmailAndListId')
            ->willReturn(false);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact');

        $this->sut->addContact($addContactCommand);

        self::assertEquals(1, $addContactCommand->getContactCustomFieldsCollection()->count());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldAddContact()
    {
        $addContactCommand = new AddContactCommand(
            'example@exmple.com',
            'name',
            'listId',
            null,
            new ContactCustomFieldsCollection()
        );

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getOriginCustomFieldId')
            ->willReturn('oid');

        $this->customFieldServiceMock
            ->expects(self::never())
            ->method('getCustomFieldByName');

        $this->customFieldServiceMock
            ->expects(self::never())
            ->method('createCustomField');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('setOriginCustomFieldId');

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('findContactByEmailAndListId')
            ->willReturn(false);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact');

        $this->sut->addContact($addContactCommand);

        self::assertEquals(1, $addContactCommand->getContactCustomFieldsCollection()->count());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldAddContactWhenWrongOriginId()
    {
        $addContactCommand = new AddContactCommand(
            'example@exmple.com',
            'name',
            'listId',
            null,
            new ContactCustomFieldsCollection()
        );

        $this->dbRepositoryMock
            ->expects(self::exactly(2))
            ->method('getOriginCustomFieldId')
            ->willReturnOnConsecutiveCalls('oid', '');

        $this->customFieldServiceMock
            ->expects(self::once())
            ->method('getCustomFieldByName')
            ->willReturn(null);


        $this->customFieldServiceMock
            ->expects(self::once())
            ->method('createCustomField')
            ->willReturn(['customFieldId' => 'newoid']);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('setOriginCustomFieldId')
            ->with('newoid');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('clearOriginCustomField');

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('findContactByEmailAndListId')
            ->willReturn(false);

        $createContactCall = 0;
        $callback = function () use (&$createContactCall) {
            $createContactCall++;
            if (1 == $createContactCall) {
                throw CustomFieldNotFoundException::createWithCustomFieldId('oid');
            } else {return true; }
        };

        $this->getResponseApiClientMock
            ->expects(self::exactly(2))
            ->method('createContact')
            ->will($this->returnCallback($callback));

        $this->sut->addContact($addContactCommand);

        self::assertEquals(1, $addContactCommand->getContactCustomFieldsCollection()->count());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldUpdateContactIfAlreadyExists()
    {
        $email = 'mail@example.com';

        $contact = [
            'contactId' => 'xyd1',
            'name' => 'John',
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => 'x1',
                    'name' => 'origin',
                    'value' => ['value'],
                    'values' => ['value'],
                    'type' => 'text',
                    'fieldType' => 'text',
                    'valueType' => 'string',
                ]
            ]
        ];

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('findContactByEmailAndListId')
            ->with($email, 'lid')
            ->willReturn($contact);

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('updateContact')
            ->with('xyd1');

        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();
        $contactCustomFieldsCollection->add(
            new ContactCustomField('x2', ['value2'])
        );

        $addContactCommand = new AddContactCommand(
            $email, 'Janko', 'lid', 1, new ContactCustomFieldsCollection(), true
        );

        $this->sut->addContact($addContactCommand);

        self::assertNull($addContactCommand->getDayOfCycle());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldUnsubscribeContact()
    {
        $email = 'test@test.com';
        $originCustomFieldId = 'oid';

        $contact1 = [
            'contactId' => 'xyd1',
            'name' => 'John',
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => $originCustomFieldId,
                    'name' => 'origin',
                    'value' => [$this->originCustomName],
                    'values' => [$this->originCustomName],
                    'type' => 'text',
                    'fieldType' => 'text',
                    'valueType' => 'string',
                ]
            ]
        ];

        $contact2 = [
            'contactId' => 'xyd2',
            'name' => 'John',
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => $originCustomFieldId,
                    'name' => 'origin',
                    'value' => [$this->originCustomName],
                    'values' => [$this->originCustomName],
                    'type' => 'text',
                    'fieldType' => 'text',
                    'valueType' => 'string',
                ],
                [
                    'customFieldId' => 'aaa',
                    'name' => 'origin',
                    'value' => ['value'],
                    'values' => ['value'],
                    'type' => 'text',
                    'fieldType' => 'text',
                    'valueType' => 'string',
                ]
            ]
        ];

        $contact3 = [
            'contactId' => 'xyd2',
            'name' => 'John',
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => 'bbb',
                    'name' => 'origin',
                    'value' => ['value'],
                    'values' => ['value'],
                    'type' => 'text',
                    'fieldType' => 'text',
                    'valueType' => 'string',
                ]
            ]
        ];

        $contact4 = [
            'contactId' => 'xyd3',
            'name' => 'John',
            'email' => $email,
        ];

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getOriginCustomFieldId')
            ->willReturn($originCustomFieldId);

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('searchContacts')
            ->with($email, true)
            ->willReturn([$contact1, $contact2, $contact3, $contact4]);

        $this->getResponseApiClientMock
            ->expects(self::exactly(2))
            ->method('deleteContact')
            ->withConsecutive(['xyd1'], ['xyd2']);

        $this->sut->unsubscribeContacts(new UnsubscribeContactsCommand($email));
    }

}
