<?php
namespace GrShareCode\Tests\Unit\Domain\Export;

use GrShareCode\Contact\ContactService;
use GrShareCode\Export\ExportContactService;
use GrShareCode\Export\Settings\ExportSettingsFactory;
use GrShareCode\GetresponseApiException;
use GrShareCode\Order\OrderService;
use GrShareCode\Tests\Generator;
use GrShareCode\Tests\Unit\BaseTestCase;

class ExportContactServiceTest extends BaseTestCase
{

    /** @var ContactService|\PHPUnit_Framework_MockObject_MockObject */
    private $contactServiceMock;
    /** @var OrderService|\PHPUnit_Framework_MockObject_MockObject */
    private $orderServiceMock;
    /** @var ExportContactService */
    private $sut;

    public function setUp()
    {
        $this->contactServiceMock = $this->getMockWithoutConstructing(ContactService::class);
        $this->orderServiceMock = $this->getMockWithoutConstructing(OrderService::class);

        $this->sut = new ExportContactService(
            $this->contactServiceMock,
            $this->orderServiceMock
        );
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldAddContact()
    {
        $exportSettings = ExportSettingsFactory::createFromArray([
            'contactListId' => 'contactListId',
            'dayOfCycle' => null,
            'ecommerceEnabled' => false,
            'shopId' => null
        ]);

        $this->contactServiceMock
            ->expects(self::once())
            ->method('addContact');

        $this->orderServiceMock
            ->expects(self::never())
            ->method('addOrder');

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);
        $this->sut->exportContact($exportContactCommand);
    }

    /**
     * @test
     * @throws GetresponseApiException
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

        $this->contactServiceMock
            ->expects(self::once())
            ->method('addContact');

        $this->orderServiceMock
            ->expects(self::exactly(2))
            ->method('addOrder');

        $exportContactCommand = Generator::createExportContactCommandWithSettings($exportSettings);
        $this->sut->exportContact($exportContactCommand);
    }

}
