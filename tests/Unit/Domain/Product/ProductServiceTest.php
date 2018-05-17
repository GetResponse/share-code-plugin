<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductService;
use GrShareCode\ProductMapping\ProductMapping;
use GrShareCode\Tests\Generator;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    /** @var DbRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $dbRepositoryMock;

    /** @var GetresponseApi|\PHPUnit_Framework_MockObject_MockObject */
    private $grApiMock;

    public function setUp()
    {
        $this->dbRepositoryMock = $this->getMockBuilder(DbRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->grApiMock = $this->getMockBuilder(GetresponseApi::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function shouldCreateProductAndVariant()
    {
        $products = Generator::createProductsCollection();
        $nullProductMapping = new ProductMapping(null, null, null, null, null);
        $shopId = 1;

        /** @var Product $product */
        foreach ($products as $product) {

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('getProductMappingByVariantId')
                ->with($shopId, $product->getExternalId(), $product->getVariant()->getExternalId())
                ->willReturn($nullProductMapping);

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('getProductMappingByProductId')
                ->with($shopId, $product->getExternalId())
                ->willReturn($nullProductMapping);

            $grProductParams = [
                'name' => $product->getName(),
                'categories' => $product->getCategories()->toRequestArray(),
                'variants' => [$product->getVariant()->toRequestArray()],
            ];

            $this->grApiMock
                ->expects($this->once())
                ->method('createProduct')
                ->with($shopId, $grProductParams);

            $productMapping = new ProductMapping(
                $product->getExternalId(),
                $product->getVariant()->getExternalId(),
                $shopId,
                null,
                null
            );

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('saveProductMapping')
                ->with($productMapping);

            $productService = new ProductService($this->grApiMock, $this->dbRepositoryMock);

            $expected = [
                'variantId' => null,
                'price' => $product->getProductVariant()->getPrice(),
                'priceTax' => $product->getProductVariant()->getPriceTax(),
                'quantity' => $product->getProductVariant()->getQuantity(),
                'images' => [
                    0 => [
                        'src' => $product->getProductVariant()->getImages()->getIterator()[0]->getSrc(),
                        'position' => $product->getProductVariant()->getImages()->getIterator()[0]->getPosition()
                    ]
                ]
            ];

            $this->assertEquals([$expected], $productService->getProductVariants($products, $shopId));
        }
    }

    /**
     * @test
     */
    public function shouldCreateProductVariant()
    {
        $shopId = 1;
        $grProductId = 13;
        $grVariantId = null;
        $products = Generator::createProductsCollection();
        $nullProductMapping = new ProductMapping(null, null, null, null, null);
        $productMapping = new ProductMapping(11, 12, $shopId, $grProductId, $grVariantId);

        /** @var Product $product */
        foreach ($products as $product) {

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('getProductMappingByVariantId')
                ->with($shopId, $product->getExternalId(), $product->getVariant()->getExternalId())
                ->willReturn($nullProductMapping);

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('getProductMappingByProductId')
                ->with($shopId, $product->getExternalId())
                ->willReturn($productMapping);

            $this->grApiMock
                ->expects($this->once())
                ->method('createProductVariant');

            $productMapping = new ProductMapping(
                $product->getExternalId(),
                $product->getVariant()->getExternalId(),
                $shopId,
                $grProductId,
                $grVariantId
            );

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('saveProductMapping')
                ->with($productMapping);

            $productService = new ProductService($this->grApiMock, $this->dbRepositoryMock);

            $expected = [
                'variantId' => $grVariantId,
                'price' => $product->getProductVariant()->getPrice(),
                'priceTax' => $product->getProductVariant()->getPriceTax(),
                'quantity' => $product->getProductVariant()->getQuantity(),
                'images' => [
                    0 => [
                        'src' => $product->getProductVariant()->getImages()->getIterator()[0]->getSrc(),
                        'position' => $product->getProductVariant()->getImages()->getIterator()[0]->getPosition()
                    ]
                ]
            ];

            $this->assertEquals([$expected], $productService->getProductVariants($products, $shopId));
        }
    }

    /**
     * @test
     */
    public function shouldReturnExistingProductVariant()
    {
        $shopId = 1;
        $grProductId = 13;
        $grVariantId = 14;
        $products = Generator::createProductsCollection();
        $productMapping = new ProductMapping(11, 12, $shopId, $grProductId, $grVariantId);

        /** @var Product $product */
        foreach ($products as $product) {

            $this->dbRepositoryMock
                ->expects($this->once())
                ->method('getProductMappingByVariantId')
                ->with($shopId, $product->getExternalId(), $product->getVariant()->getExternalId())
                ->willReturn($productMapping);

            $this->grApiMock
                ->expects($this->never())
                ->method('createProduct');

            $this->grApiMock
                ->expects($this->never())
                ->method('createProductVariant');

            $this->dbRepositoryMock
                ->expects($this->never())
                ->method('saveProductMapping');

            $productService = new ProductService($this->grApiMock, $this->dbRepositoryMock);

            $expected = [
                'variantId' => $grVariantId,
                'price' => $product->getProductVariant()->getPrice(),
                'priceTax' => $product->getProductVariant()->getPriceTax(),
                'quantity' => $product->getProductVariant()->getQuantity(),
                'images' => [
                    0 => [
                        'src' => $product->getProductVariant()->getImages()->getIterator()[0]->getSrc(),
                        'position' => $product->getProductVariant()->getImages()->getIterator()[0]->getPosition()
                    ]
                ]
            ];

            $this->assertEquals([$expected], $productService->getProductVariants($products, $shopId));
        }
    }
}
