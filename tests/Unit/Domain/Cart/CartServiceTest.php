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
    /** @var CartService */
    private $sut;

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

        $this->sut = new CartService($this->grApiMock, $this->dbRepositoryMock, $this->productServiceMock);
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
        $variants[] = [
            'variantId' => 'abc',
            'price' => 100,
            'priceTax' => 120,
            'quantity' => 1
        ];

        $createCartPayload = [
            'contactId' => $contact['contactId'],
            'currency' => $command->getCart()->getCurrency(),
            'totalPrice' => $command->getCart()->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId' => $command->getCart()->getCartId(),
            'totalTaxPrice' => $command->getCart()->getTotalTaxPrice(),
        ];

        $this->grApiMock
            ->expects(self::once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn($contact);

        $this->productServiceMock
            ->expects(self::once())
            ->method('getProductsVariants')
            ->with(
                $command->getCart()->getProducts(),
                $command->getShopId()
            )->willReturn($variants);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrCartIdFromMapping')
            ->with($command->getShopId(), $command->getCart()->getCartId())
            ->willReturn(null);

        $this->grApiMock
            ->expects(self::once())
            ->method('createCart')
            ->with($command->getShopId(), $createCartPayload)
            ->willReturn('grCartId');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('saveCartMapping')
            ->with($command->getShopId(), $command->getCart()->getCartId(), 'grCartId');


        $this->sut->sendCart($command);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldUpdateCartInGetResponse()
    {
        $command = Generator::createAddCartCommand();
        $contact = ['contactId' => 1];
        $variants[] = [
            'variantId' => 'abc',
            'price' => 100,
            'priceTax' => 120,
            'quantity' => 1
        ];

        $createCartPayload = [
            'contactId' => $contact['contactId'],
            'currency' => $command->getCart()->getCurrency(),
            'totalPrice' => $command->getCart()->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId' => $command->getCart()->getCartId(),
            'totalTaxPrice' => $command->getCart()->getTotalTaxPrice(),
        ];

        $this->grApiMock
            ->expects(self::once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn($contact);

        $this->productServiceMock
            ->expects(self::once())
            ->method('getProductsVariants')
            ->with(
                $command->getCart()->getProducts(),
                $command->getShopId()
            )->willReturn($variants);

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('getGrCartIdFromMapping')
            ->with($command->getShopId(), $command->getCart()->getCartId())
            ->willReturn('grCartId');

        $this->grApiMock
            ->expects(self::never())
            ->method('createCart');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('saveCartMapping');

        $this->grApiMock
            ->expects(self::once())
            ->method('updateCart')
            ->with($command->getShopId(), 'grCartId', $createCartPayload);

        $this->sut->sendCart($command);
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
            ->method('getProductsVariants');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('getGrCartIdFromMapping');

        $this->grApiMock
            ->expects($this->never())
            ->method('createCart');

        $this->grApiMock
            ->expects($this->never())
            ->method('updateCart');

        $this->sut->sendCart($command);
    }
}
