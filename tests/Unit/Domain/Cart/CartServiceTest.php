<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\Cache\CacheInterface;
use GrShareCode\Cart\CartService;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
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
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;
    /** @var ProductService|\PHPUnit_Framework_MockObject_MockObject */
    private $productServiceMock;
    /** @var CacheInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $cacheMock;
    /** @var CartService */
    private $sut;

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

        $this->cacheMock = $this->getMockBuilder(CacheInterface::class)
            ->getMock();

        $this->sut = new CartService($this->grApiClientMock, $this->dbRepositoryMock, $this->productServiceMock, $this->cacheMock);
    }


    /**
     * @test
     * @doesNotPerformAssertions
     * @throws GetresponseApiException
     */
    public function shouldNotSentCartIfAlreadySentWithSameData()
    {
        $command = Generator::createAddCartCommand();
        $variants[] = [
            'variantId' => 'abc',
            'price' => 100,
            'priceTax' => 120,
            'quantity' => 1
        ];

        $this->cacheMock
            ->expects(self::once())
            ->method('get')
            ->with('cart_key' . $command->getCart()->getCartId())
            ->willReturn('4a3afe4fab8f35cc2d9b92b580c8d7bd');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('getContactByEmail');

        $this->productServiceMock
            ->expects(self::never())
            ->method('getProductsVariants');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('getGrCartIdFromMapping');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('createCart');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('saveCartMapping');

        $this->sut->sendCart($command);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @throws GetresponseApiException
     */
    public function shouldCreateCartWithProductInGetResponseAndRetrieveContactFromCache()
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

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(
                ['cart_key' . $command->getCart()->getCartId()],
                [$command->getEmail() . '_' . $command->getContactListId()]
            )
            ->willReturnOnConsecutiveCalls('f91ec96768b279d32473d372bb3ec179XX', json_encode($contact));

        $this->cacheMock
            ->expects(self::once())
            ->method('set')
            ->with('cart_key' . $command->getCart()->getCartId(), '4a3afe4fab8f35cc2d9b92b580c8d7bd', 600);

        $this->grApiClientMock
            ->expects(self::never())
            ->method('getContactByEmail');

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

        $this->grApiClientMock
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

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['cart_key100001'], [$command->getEmail() . '_' . $command->getContactListId()])
            ->willReturnOnConsecutiveCalls('f91ec96768b279d32473d372bb3ec179XX', false);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn($contact);

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('set')
            ->withConsecutive(
                [$command->getEmail() . '_' . $command->getContactListId(), json_encode($contact), 600],
                ['cart_key' . $command->getCart()->getCartId(), '4a3afe4fab8f35cc2d9b92b580c8d7bd', 600]
            );

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

        $this->grApiClientMock
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

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['cart_key100001'], [$command->getEmail() . '_' . $command->getContactListId()])
            ->willReturnOnConsecutiveCalls('f91ec96768b279d32473d372bb3ec179XX', false);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn($contact);

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('set')
            ->withConsecutive(
                [$command->getEmail() . '_' . $command->getContactListId(), json_encode($contact), 600],
                ['cart_key' . $command->getCart()->getCartId(), '4a3afe4fab8f35cc2d9b92b580c8d7bd', 600]
            );

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

        $this->grApiClientMock
            ->expects(self::never())
            ->method('createCart');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('saveCartMapping');

        $this->grApiClientMock
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

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['cart_key100001'], [$command->getEmail() . '_' . $command->getContactListId()])
            ->willReturnOnConsecutiveCalls('f91ec96768b279d32473d372bb3ec179XX', false);

        $this->grApiClientMock
            ->expects($this->once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn([]);

        $this->cacheMock
            ->expects(self::once())
            ->method('set')
            ->with($command->getEmail() . '_' . $command->getContactListId(), json_encode([]), 600);

        $this->productServiceMock
            ->expects($this->never())
            ->method('getProductsVariants');

        $this->dbRepositoryMock
            ->expects($this->never())
            ->method('getGrCartIdFromMapping');

        $this->grApiClientMock
            ->expects($this->never())
            ->method('createCart');

        $this->grApiClientMock
            ->expects($this->never())
            ->method('updateCart');

        $this->sut->sendCart($command);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldNotCreateNewCartIfVariantsCollectionIsEmpty()
    {
        $command = Generator::createAddCartCommandWithNoVariants();
        $contact = ['contactId' => 1];
        $variants = [];

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['cart_key100001'], [$command->getEmail() . '_' . $command->getContactListId()])
            ->willReturnOnConsecutiveCalls('f91ec96768b279d32473d372bb3ec179XX', false);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn($contact);

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('set')
            ->withConsecutive(
                [$command->getEmail() . '_' . $command->getContactListId(), json_encode($contact), 600],
                ['cart_key' . $command->getCart()->getCartId(), '8b9c3edb4d700d04f4b6490f086508fd', 600]
            );

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

        $this->grApiClientMock
            ->expects(self::never())
            ->method('createCart');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('saveCartMapping');

        $this->sut->sendCart($command);
    }


    /**
     * Sytuacja ma miejsce, gdy klient w sklepie usuwa wszystkie produkty z koszyka.
     *
     * @test
     * @throws GetresponseApiException
     */
    public function shouldRemoveCartWhichAlreadyExistIfVariantsCollectionIsEmpty()
    {
        $command = Generator::createAddCartCommandWithNoVariants();
        $contact = ['contactId' => 1];
        $variants = [];


        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['cart_key100001'], [$command->getEmail() . '_' . $command->getContactListId()])
            ->willReturnOnConsecutiveCalls('f91ec96768b279d32473d372bb3ec179XX', false);

        $this->grApiClientMock
            ->expects(self::once())
            ->method('getContactByEmail')
            ->with($command->getEmail(), $command->getContactListId())
            ->willReturn($contact);

        $this->cacheMock
            ->expects(self::exactly(2))
            ->method('set')
            ->withConsecutive(
                [$command->getEmail() . '_' . $command->getContactListId(), json_encode($contact), 600],
                ['cart_key' . $command->getCart()->getCartId(), '8b9c3edb4d700d04f4b6490f086508fd', 600]
            );

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

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('removeCartMapping')
            ->with($command->getShopId(), $command->getCart()->getCartId(), 'grCartId');

        $this->grApiClientMock
            ->expects(self::once())
            ->method('removeCart')
            ->with($command->getShopId(), 'grCartId');

        $this->grApiClientMock
            ->expects(self::never())
            ->method('updateCart');

        $this->sut->sendCart($command);
    }

    /**
     * @test
     */
    public function shouldNotExportCartForContactThatNotExistsInGr()
    {
        $command = Generator::createAddCartCommand();

        $this->grApiClientMock
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

        $this->grApiClientMock
            ->expects($this->never())
            ->method('createCart');

        $this->grApiClientMock
            ->expects($this->never())
            ->method('updateCart');

        $this->sut->exportCart($command);
    }

    /**
     * @test
     */
    public function shouldNotExportCartIfCartAlreadyExistsInGr()
    {
        $command = Generator::createAddCartCommand();
        $contact = ['contactId' => 1];
        $variants[] = [
            'variantId' => 'abc',
            'price' => 100,
            'priceTax' => 120,
            'quantity' => 1
        ];

        $this->grApiClientMock
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

        $this->grApiClientMock
            ->expects(self::never())
            ->method('createCart');

        $this->dbRepositoryMock
            ->expects(self::never())
            ->method('saveCartMapping');

        $this->sut->exportCart($command);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldExportCart()
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

        $this->grApiClientMock
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

        $this->grApiClientMock
            ->expects(self::once())
            ->method('createCart')
            ->with($command->getShopId(), $createCartPayload)
            ->willReturn('grCartId');

        $this->dbRepositoryMock
            ->expects(self::once())
            ->method('saveCartMapping')
            ->with($command->getShopId(), $command->getCart()->getCartId(), 'grCartId');


        $this->sut->exportCart($command);

    }
}
