<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Product\ProductService;
use GrShareCode\Product\Variant\Images\Image;
use GrShareCode\Product\Variant\Variant;
use GrShareCode\ProductMapping\ProductMapping;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;
    /** @var GetresponseApiClient|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiClientMock;
    /** @var ProductService */
    private $sut;

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->grApiClientMock = $this->getMockBuilder(GetresponseApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new ProductService($this->grApiClientMock, $this->dbRepositoryMock);
    }

    /**
     * @test
     */
    public function shouldCreateProductAndVariant()
    {
        $products = Generator::createProductsCollection(2, 2);
        $nullProductMapping = Generator::createEmptyProductMapping();
        $shopId = 1;

        $this->dbRepositoryMock
            ->expects(self::exactly(3))
            ->method('getProductMappingByProductId')
            ->withConsecutive([$shopId, 1], [$shopId, 1],[$shopId, 2])
            ->willReturnOnConsecutiveCalls(
                $nullProductMapping,
                new ProductMapping(1, 'v1', $shopId, 'p1', 'v11'),
                new ProductMapping(2, 'v2', $shopId, 'p2', 'v21')
            );

        $this->grApiClientMock
            ->expects(self::exactly(1))
            ->method('createProduct')
            ->with($shopId, $this->buildProductParams($products->getIterator()[0]))
            ->willReturn(
                ['productId' => 'p1', 'variants' => [['externalId' => 1, 'variantId' => 'v11'],['externalId' => 2, 'variantId' => 'v12']]]
            );

        /** @var Product $product2 */
        $product2 = $products->getIterator()[1];
        /** @var Variant $variant22 */
        $variant22 = $product2->getVariants()->getIterator()[1];


        $this->grApiClientMock
            ->expects(self::once())
            ->method('createProductVariant')
            ->with($shopId, 'p2', $variant22->toRequestArray())
            ->willReturn($variant22->toRequestArrayWithVariantId('v22'));


        $this->dbRepositoryMock
            ->expects(self::exactly(4))
            ->method('getProductMappingByVariantId')
            ->withConsecutive(
                [$shopId, 1, 1],
                [$shopId, 1, 2],
                [$shopId, 2, 1],
                [$shopId, 2, 2]
            )
            ->willReturnOnConsecutiveCalls(
                new ProductMapping(1, 1, $shopId, 'p1', 'v11'),
                new ProductMapping(1, 2, $shopId, 'p1', 'v12'),
                new ProductMapping(2, 1, $shopId, 'p2', 'v21'),
                $nullProductMapping
            );


        $this->dbRepositoryMock
            ->expects(self::exactly(3))
            ->method('saveProductMapping')
            ->withConsecutive(
                new ProductMapping(1, 1, $shopId, 'p1', 'v11'),
                new ProductMapping(1, 2, $shopId, 'p1', 'v12'),
                new ProductMapping(2, 2, $shopId, 'p2', 'v22')
            );

        $this->dbRepositoryMock
            ->expects(self::exactly(4))
            ->method('getProductMappingByVariantId')
            ->withConsecutive(
                [$shopId, 1, 1],
                [$shopId, 1, 2],
                [$shopId, 2, 1],
                [$shopId, 2, 2]
            )
            ->willReturnOnConsecutiveCalls(
                new ProductMapping(1, 1, $shopId, 'p1', 'v11'),
                new ProductMapping(1, 2, $shopId, 'p1', 'v12'),
                new ProductMapping(2, 1, $shopId, 'p2', 'v21'),
                new ProductMapping(2, 2, $shopId, 'p2', 'v22')
            );

        $expected = [
            $this->buildVariantResponse($products, 0, 0, 'v11'),
            $this->buildVariantResponse($products, 0, 1, 'v12'),
            $this->buildVariantResponse($products, 1, 0, 'v21'),
            $this->buildVariantResponse($products, 1, 1, 'v22'),
        ];

        self::assertEquals($expected, $this->sut->getProductsVariants($products, $shopId));
    }

    /**
     * @param Product $product
     * @return array
     */
    private function buildProductParams(Product $product)
    {
        return [
            'name' => $product->getName(),
            'externalId' => $product->getExternalId(),
            'categories' => $product->getCategories()->toRequestArray(),
            'variants' => $product->getVariants()->toRequestArray(),
            'url' => 'getresponse.com'
        ];
    }


    /**
     * @param ProductsCollection $products
     * @param int $productIndex
     * @param int $variantIndex
     * @param string $grVariantId
     * @return array
     */
    private function buildVariantResponse(ProductsCollection $products, $productIndex, $variantIndex, $grVariantId)
    {
        /** @var Product $product */
        $product = $products->getIterator()[$productIndex];
        /** @var Variant $variant */
        $variant = $product->getVariants()->getIterator()[$variantIndex];
        /** @var Image $image */
        $image = $variant->getImages()->getIterator()[0];

        return [
            'variantId' => $grVariantId,
            'price' => $variant->getPrice(),
            'priceTax' => $variant->getPriceTax(),
            'quantity' => $variant->getQuantity(),
            'images' => [
                [
                    'src' => $image->getSrc(),
                    'position' => $image->getPosition()
                ]
            ]
        ];
    }
}
