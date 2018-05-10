<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\Cart\CartService;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\Tests\Faker;
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
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)->disableOriginalConstructor()
            ->getMock();
        $this->grApiMock = $this->getMockBuilder(GetresponseApi::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldCreateCartWithProductInGetResponse()
    {
        $command = Faker::createAddCartCommand();

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);
        $this->grApiMock->expects($this->once())->method('createProduct');

        $this->grApiMock->expects($this->once())->method('createCart');
        $this->dbRepositoryMock->expects($this->once())->method('saveCartMapping');

        $this->dbRepositoryMock->method('getProductVariantById')->willReturn(null);
        $this->dbRepositoryMock->expects($this->once())->method('saveProductMapping');

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function shouldUpdateCartInGetResponse()
    {
        $command = Faker::createAddCartCommand();

        $contact = ['contactId' => 1];

        $this->grApiMock->method('getContactByEmail')->willReturn($contact);
        $this->grApiMock->expects($this->once())->method('updateCart');

        $this->dbRepositoryMock->method('getGrCartIdFromMapping')->willReturn(3);

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenContactNotFound()
    {
        $this->expectException(ContactNotFoundException::class);

        $command = Faker::createAddCartCommand();

        $this->grApiMock->method('getContactByEmail')->willThrowException(new ContactNotFoundException());

        $cartService = new CartService($this->grApiMock, $this->dbRepositoryMock);

        $cartService->sendCart($command);
    }
}
