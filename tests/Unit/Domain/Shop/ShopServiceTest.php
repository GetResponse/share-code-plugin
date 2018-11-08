<?php
namespace GrShareCode\Tests\Unit\Domain\Shop\ShopServiceTest;

use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\Shop\Shop;
use GrShareCode\Shop\ShopService;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class ShopServiceTest
 * @package GrShareCode\Tests\Unit\Domain\Shop\ShopServiceTest
 */
class ShopServiceTest extends BaseTestCase
{
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $getResponseApiClientMock;
    /** @var ShopService */
    private $sut;

    public function setUp()
    {
        $this->getResponseApiClientMock = $this->getMockWithoutConstructing(GetresponseApiClient::class);
        $this->sut = new ShopService($this->getResponseApiClientMock);
    }

    /**
     * @test
     * @throws GetresponseApiException
     */
    public function shouldGetShops()
    {
        $this->getResponseApiClientMock
            ->expects(self::once())
            ->method('getShops')
            ->willReturn([
                [
                    'shopId' => 'id1',
                    'name' => 'name1'
                ],
                [
                    'shopId' => 'id2',
                    'name' => 'name2'
                ],
            ]);

        $collection = $this->sut->getAllShops();
        self::assertEquals(2, $collection->count());

        /** @var Shop $first */
        $first = $collection->get(0);
        self::assertEquals('id1', $first->getId());
        self::assertEquals('name1', $first->getName());
    }

}
