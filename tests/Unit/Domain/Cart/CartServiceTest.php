<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\CartService;
use GrShareCode\Cart\ContactNotFoundException;
use GrShareCode\Product\Product;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\Product\ProductsCollection;
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

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)->disableOriginalConstructor()->getMock();
        $this->grApiMock = $this->getMockBuilder(GetresponseApi::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldCreateCartInGetResponse()
    {
        $product = new Product(1, 'simple product', 3, 9.99, 12.00);
        $products = new ProductsCollection();
        $products->add($product);
        $command = new AddCartCommand('simple@example.com','listId', $products, 'cartId', null, 'currency', 9.99, 12.00);

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);
        $this->grApiMock->expects($this->once())->method('createCart');
        $this->dbRepositoryMock->expects($this->once())->method('saveCartMapping');

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldCreateProductInGetResponse()
    {
        $product = new Product(1, 'simple product', 3, 9.99, 12.00);
        $products = new ProductsCollection();
        $products->add($product);
        $command = new AddCartCommand('simple@example.com','listId', $products, 'cartId', null, 'currency', 9.99, 12.00);

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);
        $this->grApiMock->expects($this->once())->method('createProduct');

        $this->dbRepositoryMock->method('getProductVariantById')->willReturn(null);
        $this->dbRepositoryMock->expects($this->once())->method('saveProductVariant');

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldUpdateCartInGetResponse()
    {
        $product = new Product(1, 'simple product', 3, 9.99, 12.00);
        $products = new ProductsCollection();
        $products->add($product);
        $command = new AddCartCommand('simple@example.com','listId', $products, 'cartId', 'grCartId', 'currency', 9.99, 12.00);

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);
        $this->grApiMock->expects($this->once())->method('updateCart');

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenContactNotFound()
    {
        $this->expectException(ContactNotFoundException::class);

        $product = new Product(1, 'simple product', 3, 9.99, 12.00);
        $products = new ProductsCollection();
        $products->add($product);
        $command = new AddCartCommand('simple@example.com','listId', $products, 'cartId', 'grCartId', 'currency', 9.99, 12.00);

        $this->grApiMock->method('getContactByEmail')->willThrowException(new ContactNotFoundException());

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }
}
