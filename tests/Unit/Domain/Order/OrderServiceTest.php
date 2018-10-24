<?php
namespace GrShareCode\Tests\Unit\Domain\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Order\OrderService;
use GrShareCode\Product\ProductService;
use GrShareCode\Tests\Generator;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class OrderServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Order
 */
class OrderServiceTest extends BaseTestCase
{
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;
    /** @var ProductService|\PHPUnit_Framework_MockObject_MockObject */
    private $productServiceMock;
    /** @var OrderService */
    private $sut;

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockWithoutConstructing(DbRepositoryInterface::class);
        $this->grApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->productServiceMock = $this->getMockWithoutConstructing(ProductService::class);
        $this->sut = new OrderService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock);
    }

    /**
     * @test
     * @throws \GrShareCode\GetresponseApiException
     */
    public function shouldCreateOrder()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiClientMock
            ->expects(self::once())
            ->method('findContactByEmailAndListId')
            ->willReturn($contact);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrOrderIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn([]);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrCartIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalCartId())
            ->willReturn(10);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('createOrder');

        $this->grApiClientMock
            ->expects(self::once())
            ->method('removeCart');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('saveOrderMapping');

        $this->sut->sendOrder($addOrderCommand);
    }

    /**
     * @test
     * @throws \GrShareCode\GetresponseApiException
     */
    public function shouldNotSendOrderForContactThatNotExistsInGr()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $this->grApiClientMock
            ->expects(self::once())
            ->method('findContactByEmailAndListId')
            ->willReturn([]);

        $this->grApiClientMock
            ->expects(self::never())
            ->method('updateOrder');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('createOrder');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('removeCart');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);

    }

}
