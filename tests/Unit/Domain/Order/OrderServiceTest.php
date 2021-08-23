<?php
namespace GrShareCode\Tests\Unit\Domain\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Order\OrderPayloadFactory;
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

        $this->sut = new OrderService(
            $this->grApiClientMock,
            $this->dbRepositoryMock,
            $this->productServiceMock,
            new OrderPayloadFactory()
        );
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldAddOrder()
    {
        $addOrderCommand = Generator::createAddOrderCommand();

        $contact = ['contactId' => 1];
        $this->grApiClientMock
            ->expects(self::once())
            ->method('findContactByEmailAndListId')
            ->willReturn($contact);

        $this->productServiceMock
            ->expects(self::once())
            ->method('getProductsVariants')
            ->willReturn([]);

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
            ->method('removeCart')
            ->with($addOrderCommand->getShopId(), 10);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('createOrder')
            ->willReturn('oid');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('saveOrderMapping')
            ->with(
                $addOrderCommand->getShopId(),
                $addOrderCommand->getOrder()->getExternalOrderId(),
                'oid',
                'e59102ea0ab7ae61286718a1e3c6d1b1'
            );

        $this->sut->addOrder($addOrderCommand);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldNotAddOrderForContactThatNotExistsInGr()
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

        $this->sut->addOrder($addOrderCommand);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldUpdateOrder()
    {
        $editOrderCommand = Generator::createEditOrderCommand();

        $this->productServiceMock
            ->expects(self::once())
            ->method('getProductsVariants')
            ->willReturn([]);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrOrderIdFromMapping')
            ->with($editOrderCommand->getShopId(), $editOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn('oid');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getPayloadMd5FromOrderMapping')
            ->with($editOrderCommand->getShopId(), $editOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn('aasdadasdsdadssa');

        $this->grApiClientMock
            ->expects(self::once())
            ->method('updateOrder');

        $this->sut->updateOrder($editOrderCommand);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldNotUpdateOrderIfPayloadDoesntChange()
    {
        $editOrderCommand = Generator::createEditOrderCommand();

        $this->productServiceMock
            ->expects(self::once())
            ->method('getProductsVariants')
            ->willReturn([]);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrOrderIdFromMapping')
            ->with($editOrderCommand->getShopId(), $editOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn('oid');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getPayloadMd5FromOrderMapping')
            ->with($editOrderCommand->getShopId(), $editOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn('e59102ea0ab7ae61286718a1e3c6d1b1');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('updateOrder');

        $this->sut->updateOrder($editOrderCommand);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldNotUpdateOrderIfOrderIsNotMapped()
    {
        $editOrderCommand = Generator::createEditOrderCommand();

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrOrderIdFromMapping')
            ->with($editOrderCommand->getShopId(), $editOrderCommand->getOrder()->getExternalOrderId())
            ->willReturn(null);

        $this->grApiClientMock
            ->expects(self::never())
            ->method('updateOrder');

        $this->sut->updateOrder($editOrderCommand);
    }

}
