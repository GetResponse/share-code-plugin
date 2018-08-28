<?php
namespace GrShareCode\Tests\Unit\Domain\Contact;

use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class ContactServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Contact
 */
class ContactServiceTest extends TestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
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
        $this->getResponseApiClientMock
            ->expects($this->once())
            ->method('createContact')
            ->with($params);

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
