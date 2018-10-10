<?php
namespace GrShareCode\Tests\Unit\Domain\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Order\OrderService;
use GrShareCode\Product\ProductService;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;

    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;

    /** @var ProductService|\PHPUnit_Framework_MockObject_MockObject */
    private $productServiceMock;

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->grApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productServiceMock = $this->getMockBuilder(ProductService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldCreateOrder()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiClientMock
            ->method('getContactByEmail')
            ->willReturn($contact);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getGrOrderIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn([]);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getGrCartIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalCartId())
            ->willReturn(10);

        $this->grApiClientMock
            ->expects($this->once())
            ->method('createOrder');

        $this->grApiClientMock
            ->expects($this->once())
            ->method('removeCart');

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);
    }

    /**
     * @test
     */
    public function shouldUpdateOrder()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiClientMock
            ->method('getContactByEmail')
            ->willReturn($contact);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getGrOrderIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn(3);

        $this->grApiClientMock
            ->expects($this->once())
            ->method('updateOrder');

        $orderService = new OrderService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);
    }

    /**
     * @test
     */
    public function shouldNotSendOrderForContactThatNotExistsInGr()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $this->grApiClientMock
            ->method('getContactByEmail')
            ->willReturn([]);

        $this->grApiClientMock
            ->expects($this->never())
            ->method('updateOrder');

        $this->grApiClientMock
            ->expects($this->never())
            ->method('createOrder');

        $this->grApiClientMock
            ->expects($this->never())
            ->method('removeCart');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);

    }

    /**
     * @test
     */
    public function shouldUpdateOrderIfPayloadNotChanged()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiClientMock
            ->method('getContactByEmail')
            ->willReturn($contact);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getGrOrderIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn(3);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getPayloadMd5FromOrderMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn('d065abbedbfa77b8da3a7a1191068317');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('updateOrder');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);
    }

}
