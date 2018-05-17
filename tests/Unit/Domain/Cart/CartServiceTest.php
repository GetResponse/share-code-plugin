<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\Cart\CartService;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\ProductService;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class CartServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Cart
 */
class CartServiceTest extends TestCase
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
     * @doesNotPerformAssertions
     * @throws GetresponseApiException
     */
    public function shouldCreateCartWithProductInGetResponse()
    {
        $command = Generator::createAddCartCommand();

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);

        $this->grApiMock->expects($this->once())->method('createCart');
        $this->dbRepositoryMock->expects($this->once())->method('saveCartMapping');

        $this->dbRepositoryMock->method('getProductMappingByVariantId')->willReturn(null);
        $this->dbRepositoryMock->expects($this->once())->method('saveCartMapping');

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldUpdateCartInGetResponse()
    {
        $command = Generator::createAddCartCommand();

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);
        $this->grApiMock->expects($this->once())->method('updateCart');

        $this->dbRepositoryMock->method('getGrCartIdFromMapping')->willReturn(3);

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     */
    public function shouldNotSendCartForContactThatNotExistsInGr()
    {
        $command = Generator::createAddCartCommand();

        $this->grApiMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn([]);

        $this->productServiceMock
            ->expects($this->never())
            ->method('getProductVariants');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('getGrCartIdFromMapping');

        $this->grApiMock
            ->expects($this->never())
            ->method('createCart');

        $this->grApiMock
            ->expects($this->never())
            ->method('updateCart');

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);
        $cartService->sendCart($command);
    }
}
