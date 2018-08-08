<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
use GrShareCode\GetresponseApi;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class ContactServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
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

    /**
     * @test
     */
    public function shouldUnsubscribeContact()
    {
        $email = 'test@test.com';
        $origin = 'shopify';

        $this->getResponseApiMock->expects(self::once())->method('searchContacts')->with($email)->willReturn([
            [
                'contactId' => 'xyd',
                'origin' => $origin
            ]
        ]);
        $this->getResponseApiMock->expects(self::once())->method('deleteContact');

        $contactService = new ContactService($this->getResponseApiMock);
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
        $this->getResponseApiMock->expects(self::once())->method('searchContacts')->with($email)->willReturn([
            [
                'contactId' => 'xyd',
                'origin' => $origin
            ]
        ]);
        $this->getResponseApiMock->expects(self::never())->method('deleteContact');

        $contactService = new ContactService($this->getResponseApiMock);
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
