<?php
namespace GrShareCode\Tests\Unit\Domain\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\Order\OrderService;
use GrShareCode\Product\ProductService;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;

    /** @var GetresponseApi|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiMock;

    /** @var ProductService|\PHPUnit_Framework_MockObject_MockObject */
    private $productServiceMock;

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->grApiMock = $this->getMockBuilder(GetresponseApi::class)
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
        $this->grApiMock
            ->method('getContactByEmail')
            ->willReturn($contact);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getGrOrderIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn([]);

        $this->grApiMock
            ->expects($this->once())
            ->method('createOrder');

        $this->grApiMock
            ->expects($this->once())
            ->method('removeCart');

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);
    }

    /**
     * @test
     */
    public function shouldUpdateOrder()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiMock
            ->method('getContactByEmail')
            ->willReturn($contact);

        $this->dbRepositoryMock
            ->expects($this->once())
            ->method('getGrOrderIdFromMapping')
            ->with($addOrderCommand->getShopId(), $addOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn(3);

        $this->grApiMock
            ->expects($this->once())
            ->method('updateOrder');

        $orderService = new OrderService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);
    }

    /**
     * @test
     */
    public function shouldNotSendOrderForContactThatNotExistsInGr()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $this->grApiMock
            ->method('getContactByEmail')
            ->willReturn([]);

        $this->grApiMock
            ->expects($this->never())
            ->method('updateOrder');

        $this->grApiMock
            ->expects($this->never())
            ->method('createOrder');

        $this->grApiMock
            ->expects($this->never())
            ->method('removeCart');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);

    }

    /**
     * @test
     */
    public function shouldUpdateOrderIfPayloadNotChanged()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiMock
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
            ->willReturn('bcf9db4827ba8b3addbb64f76537598d');

        $this->grApiMock
            ->expects(self::never())
            ->method('updateOrder');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('saveOrderMapping');

        $orderService = new OrderService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);
        $orderService->sendOrder($addOrderCommand);
    }

}
