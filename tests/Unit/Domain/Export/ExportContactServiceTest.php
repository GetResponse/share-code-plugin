<?php
namespace GrShareCode\Tests\Unit\Domain\Export;

use GrShareCode\Cart\CartService;
use GrShareCode\Contact\Contact;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
use GrShareCode\Export\ExportContactService;
use GrShareCode\Export\Settings\ExportSettingsFactory;
use GrShareCode\Order\OrderService;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class ExportContactServiceTest extends TestCase
{

    /** @var ContactService|\PHPUnit_Framework_MockObject_MockObject */
    private $contactServiceMock;

    /** @var CartService|\PHPUnit_Framework_MockObject_MockObject */
    private $cartServiceMock;

    /** @var OrderService|\PHPUnit_Framework_MockObject_MockObject */
    private $orderServiceMock;

    public function setUp()
    {
        $this->contactServiceMock = $this->getMockBuilder(ContactService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartServiceMock = $this->getMockBuilder(CartService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderServiceMock = $this->getMockBuilder(OrderService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldUpdateExistingContact()
    {
        $exportSettings = ExportSettingsFactory::createFromArray([
            'contactListId' => 'contactListId',
            'dayOfCycle' => null,
            'jobSchedulerEnabled' => false,
            'updateContactEnabled' => false,
            'ecommerceEnabled' => false,
            'shopId' => null
        ]);

        $exportCustomersService = new ExportContactService(
            $this->contactServiceMock,
            $this->cartServiceMock,
            $this->orderServiceMock
        );

        $this->contactServiceMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(new Contact(1, 'Adam Kowalski', 'adam.kowalski@getresponse.com', new ContactCustomFieldsCollection()));

        $this->contactServiceMock
            ->expects($this->once())
            ->method('updateContactOnExport');

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);
        $exportCustomersService->exportContact($exportContactCommand);
    }

    /**
     * @test
     */
    public function shouldCreateNewContact()
    {
        $exportSettings = ExportSettingsFactory::createFromArray([
            'contactListId' => 'contactListId',
            'dayOfCycle' => null,
            'jobSchedulerEnabled' => false,
            'updateContactEnabled' => false,
            'ecommerceEnabled' => false,
            'shopId' => null
        ]);

        $exportCustomersService = new ExportContactService(
            $this->contactServiceMock,
            $this->cartServiceMock,
            $this->orderServiceMock
        );

        $this->contactServiceMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willThrowException(new ContactNotFoundException());

        $this->contactServiceMock
            ->expects($this->once())
            ->method('createContact');

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);
        $exportCustomersService->exportContact($exportContactCommand);
    }

    /**
     * @test
     */
    public function shouldSendEcommerceData()
    {
        $exportSettings = ExportSettingsFactory::createFromArray([
            'contactListId' => 'contactListId',
            'dayOfCycle' => null,
            'jobSchedulerEnabled' => false,
            'updateContactEnabled' => false,
            'ecommerceEnabled' => true,
            'shopId' => 'grShopId'
        ]);

        $exportCustomersService = new ExportContactService(
            $this->contactServiceMock,
            $this->cartServiceMock,
            $this->orderServiceMock
        );

        $this->contactServiceMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->willReturn(new Contact(1, 'Adam Kowalski', 'adam.kowalski@getresponse.com', new ContactCustomFieldsCollection()));

        $this->cartServiceMock
            ->expects($this->never())
            ->method('exportCart');

        $this->orderServiceMock
            ->expects($this->once())
            ->method('sendOrder');

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);
        $exportCustomersService->exportContact($exportContactCommand);
    }

}
