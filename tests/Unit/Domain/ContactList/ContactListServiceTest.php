<?php
namespace GrShareCode\Tests\Unit\Domain\ContactList;

use GrShareCode\ContactList\Autoresponder;
use GrShareCode\ContactList\Command\AddContactListCommand;
use GrShareCode\ContactList\ContactList;
use GrShareCode\ContactList\ContactListService;
use GrShareCode\ContactList\FromFields;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBody;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubject;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ContactListServiceTest
 * @package GrShareCode\Tests\Unit\Domain\ContactList
 */
class ContactListServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var ContactListService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->sut = new ContactListService($this->getResponseApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetFromFields()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getFromFields')
            ->willReturn([
                [
                    'fromFieldId' => 'id31',
                    'email' => 'office@cocacola.com',
                    'name' => 'CocaCola',
                    'isDefault' => 'false',
                    'isActive' => 'true',
                    'createdOn' => '2017-05-04T11:26:14+0000',
                    'href' => 'https://api.getresponse.com/v3/from-fields/TyTd7'
                ],
                [
                    'fromFieldId' => 'id3',
                    'email' => 'office@pepsi.com',
                    'name' => 'CocaCola',
                    'isDefault' => 'false',
                    'isActive' => 'true',
                    'createdOn' => '2017-05-04T11:26:14+0000',
                    'href' => 'https://api.getresponse.com/v3/from-fields/TyTd7'
                ]
            ]);

        $collection = $this->sut->getFromFields();
        self::assertEquals(2, $collection->count());

        /** @var FromFields $first */
        $first = $collection->get(0);
        self::assertEquals('id31', $first->getId());
        self::assertEquals('CocaCola', $first->getName());
        self::assertEquals('office@cocacola.com', $first->getEmail());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetSubscriptionConfirmationSubjects()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getSubscriptionConfirmationSubject')
            ->willReturn([
                [
                    'subscriptionConfirmationSubjectId' => 's1',
                    'subject' => 'subject1'
                ],
                [
                    'subscriptionConfirmationSubjectId' => 's1',
                    'subject' => 'subject2'
                ]
            ]);

        $collection = $this->sut->getSubscriptionConfirmationSubjects();
        self::assertEquals(2, $collection->count());

        /** @var SubscriptionConfirmationSubject $first */
        $first = $collection->get(0);
        self::assertEquals('s1', $first->getId());
        self::assertEquals('subject1', $first->getSubject());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetSubscriptionConfirmationsBody()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getSubscriptionConfirmationBody')
            ->willReturn([
                [
                    'subscriptionConfirmationBodyId' => 's1',
                    'name' => 'name1',
                    'contentPlain' => 'contentPlain1'
                ],
                [
                    'subscriptionConfirmationBodyId' => 's2',
                    'name' => 'name2',
                    'contentPlain' => 'contentPlain2'
                ]
            ]);

        $collection = $this->sut->getSubscriptionConfirmationsBody();
        self::assertEquals(2, $collection->count());

        /** @var SubscriptionConfirmationBody $first */
        $first = $collection->get(0);
        self::assertEquals('s1', $first->getId());
        self::assertEquals('name1', $first->getName());
        self::assertEquals('contentPlain1', $first->getContentPlain());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetContactList()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getContactList')
            ->willReturn([
                [
                    'campaignId' => 'c1',
                    'name' => 'name1',
                ],
                [
                    'campaignId' => 'c2',
                    'name' => 'name2',
                ]
            ]);

        $collection = $this->sut->getAllContactLists();
        self::assertEquals(2, $collection->count());

        /** @var ContactList $first */
        $first = $collection->get(0);
        self::assertEquals('c1', $first->getId());
        self::assertEquals('name1', $first->getName());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetAutoresponders()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getAutoresponders')
            ->willReturn([
                [
                    'autoresponderId' => 'a1',
                    'name' => 'name1',
                    'campaignId' => 'c1',
                    'subject' => 'subject1',
                    'status' => 'enabled',
                    'triggerSettings' => ['dayOfCycle' => 1]
                ],
                [
                    'autoresponderId' => 'a2',
                    'name' => 'name2',
                    'campaignId' => 'c2',
                    'subject' => 'subject2',
                    'status' => 'enabled',
                    'triggerSettings' => ['dayOfCycle' => 2]
                ],

            ]);

        $collection = $this->sut->getAutoresponders();
        self::assertEquals(2, $collection->count());

        /** @var Autoresponder $first */
        $first = $collection->get(0);
        self::assertEquals('a1', $first->getId());
        self::assertEquals('name1', $first->getName());
        self::assertEquals('c1', $first->getCampaignId());
        self::assertEquals('subject1', $first->getSubject());
        self::assertEquals('enabled', $first->getStatus());
        self::assertEquals(1, $first->getCycleDay());
        self::assertTrue($first->isEnabled());
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldCreateContactList()
    {
        $addContactListCommand = new AddContactListCommand(
            'listname',
            'fid',
            'rid',
            'scbid',
            'scsid',
            'pl'
        );

        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('createContactList')
            ->with([
                'name' => 'listname',
                'confirmation' => [
                    'fromField' => ['fromFieldId' => 'fid'],
                    'replyTo' => ['fromFieldId' => 'rid'],
                    'subscriptionConfirmationBodyId' => 'scbid',
                    'subscriptionConfirmationSubjectId' => 'scsid'
                ],
                'languageCode' => 'pl'
            ]);

        $this->sut->createContactList($addContactListCommand);
    }



}