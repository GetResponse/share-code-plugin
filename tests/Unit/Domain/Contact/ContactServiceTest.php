<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\Command\FindContactCommand;
use GrShareCode\Contact\Command\GetContactCommand;
use GrShareCode\Contact\Command\UnsubscribeContactsCommand;
use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldCollectionFactory;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactFactory;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactPayloadFactory;
use GrShareCode\Contact\ContactService;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
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
    /** @var ContactFactory|\PHPUnit_Framework_MockObject_MockObject */
    private $contactFactoryMock;
    /** @var ContactCustomField */
    private $originCustomField;
    /** @var ContactService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->contactPayloadFactoryMock = $this->getMockWithoutConstructing(ContactPayloadFactory::class);
        //$this->contactFactoryMock = $this->getMockWithoutConstructing(ContactFactory::class);
        $this->originCustomField = new ContactCustomField('cid', 'wordpress');
        $this->sut = new ContactService(
            $this->getResponseApiClientMock,
            $this->contactPayloadFactoryMock,
            new ContactFactory(new ContactCustomFieldCollectionFactory()),
            $this->originCustomField
        );
    }

    /**
     * @test
     */
    public function shouldGetContact()
    {
        $id = 'grContactId';
        $name = 'Adam Kowalski';
        $email = 'adam.kowalski@getresponse.com';

        $getContactCommand = new GetContactCommand($id, true);

        $contactCustomFieldsCollection = new ContactCustomFieldsCollection();
        $contactCustomFieldsCollection->add(
            new ContactCustomField('n', 'white')
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
            new ContactCustomField('n', 'white')
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
            $this->sut->findContact($findContactCommand, true)
        );
    }

    /**
     * @test
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
    public function shouldAddContact()
    {
        /** @var AddContactCommand|\PHPUnit_Framework_MockObject_MockObject $addContactCommandMock */
        $addContactCommandMock = $this->getMockWithoutConstructing(AddContactCommand::class);

        $addContactCommandMock
            ->expects(self::once())
            ->method('addCustomField')
            ->with($this->originCustomField);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('findContactByEmailAndListId')
            ->willReturn(false);

        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact');

        $this->sut->addContact($addContactCommandMock);
    }

    /**
     * @test
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
            new ContactCustomField('x2', 'value2')
        );

        $addContactCommand = new AddContactCommand(
            $email, 'Janko', 'lid', 1, new ContactCustomFieldsCollection(), true
        );

        $this->sut->addContact($addContactCommand);
    }


    /**
     * @test
     */
    public function shouldUnsubscribeContact()
    {
        $email = 'test@test.com';

        $contact1 = [
            'contactId' => 'xyd1',
            'name' => 'John',
            'email' => $email,
            'customFieldValues' => [
                [
                    'customFieldId' => $this->originCustomField->getId(),
                    'name' => 'origin',
                    'value' => [$this->originCustomField->getValue()],
                    'values' => [$this->originCustomField->getValue()],
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
                    'customFieldId' => $this->originCustomField->getId(),
                    'name' => 'origin',
                    'value' => [$this->originCustomField->getValue()],
                    'values' => [$this->originCustomField->getValue()],
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

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('searchContacts')
            ->with($email)
            ->willReturn([$contact1, $contact2, $contact3, $contact4]);

        $this->getResponseApiClientMock
            ->expects(self::exactly(2))
            ->method('deleteContact')
            ->withConsecutive(['xyd1'], ['xyd2']);

        $this->sut->unsubscribeContacts(new UnsubscribeContactsCommand($email));
    }

}
