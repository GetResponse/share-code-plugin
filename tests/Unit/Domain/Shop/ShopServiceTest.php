<?php
namespace GrShareCode\Tests\Unit\Domain\Shop\ShopServiceTest;

use GrShareCode\GetresponseApiClient;
use GrShareCode\Shop\Shop;
use GrShareCode\Shop\ShopsCollection;
use GrShareCode\Shop\ShopService;
use PHPUnit\Framework\TestCase;

class ShopServiceTest extends TestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;

    public function setUp()
    {
        $this->grApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldReturnShopCollection()
    {
        $this->grApiClientMock
            ->expects($this->exactly(3))
            ->method('getShops')
            ->withConsecutive([1,100],[2,100],[3,100])
            ->willReturnOnConsecutiveCalls(
                [['shopId' => 'shopId_1', 'name' => 'shopName_1']],
                [['shopId' => 'shopId_2', 'name' => 'shopName_2']],
                [['shopId' => 'shopId_3', 'name' => 'shopName_3']]
            );

        $this->grApiClientMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['TotalPages' => '3']);

        $shopCollection = new ShopsCollection();
        $shopCollection->add(new Shop('shopId_1', 'shopName_1'));
        $shopCollection->add(new Shop('shopId_2', 'shopName_2'));
        $shopCollection->add(new Shop('shopId_3', 'shopName_3'));

        $shopService = new ShopService($this->grApiClientMock);
        $this->assertEquals($shopCollection, $shopService->getAllShops());
    }

}
