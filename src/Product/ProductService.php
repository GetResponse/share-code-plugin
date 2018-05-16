<?php
namespace GrShareCode\Product;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\ProductMapping\ProductMapping;

/**
 * Class ProductService
 * @package GrShareCode\Product
 */
class ProductService
{
    /** @var GetresponseApi */
    private $getresponseApi;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param GetresponseApi $getresponseApi
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $getresponseApi, DbRepositoryInterface $dbRepository)
    {
        $this->getresponseApi = $getresponseApi;
        $this->dbRepository = $dbRepository;
    }

    /**
     * @param ProductsCollection $products
     * @param string $grShopId
     * @return array
     * @throws GetresponseApiException
     */
    public function getProductVariants(ProductsCollection $products, $grShopId)
    {
        $variants = [];

        /** @var Product $product */
        foreach ($products as $product) {

            $productVariant = $product->getVariant();

            $productMapping = $this->dbRepository->getProductMappingByVariantId(
                $grShopId,
                $product->getExternalId(),
                $productVariant->getExternalId()
            );

            if ($productMapping->variantExistsInGr()) {
                $variants[] = $productVariant->toRequestArrayWithVariantId($productMapping->getGrVariantId());
                continue;
            }

            $productMapping = $this->dbRepository->getProductMappingByProductId($grShopId, $product->getExternalId());

            if (!$productMapping->productExistsInGr()) {
                $variant = $this->createProductWithVariant($grShopId, $product);
            } else {
                $variant = $this->createProductVariant($grShopId, $productMapping->getGrProductId(), $product);
            }

            $variants[] = $variant;

        }

        return $variants;
    }

    /**
     * @param string $grShopId
     * @param Product $product
     * @return array
     * @throws GetresponseApiException
     */
    private function createProductWithVariant($grShopId, Product $product)
    {
        $productVariant = $product->getVariant();

        $grProductParams = [
            'name' => $product->getName(),
            'categories' => $product->getCategories()->toRequestArray(),
            'variants' => [$productVariant->toRequestArray()],
        ];

        $grProduct = $this->getresponseApi->createProduct($grShopId, $grProductParams);

        $grVariantId = $grProduct['variants'][0]['variantId'];

        $this->dbRepository->saveProductMapping(
            new ProductMapping(
                $product->getExternalId(),
                $productVariant->getExternalId(),
                $grShopId,
                $grProduct['productId'],
                $grVariantId
            )
        );

        return $productVariant->toRequestArrayWithVariantId($grVariantId);

    }

    /**
     * @param string $grShopId
     * @param string $grProductId
     * @param Product $product
     * @return array
     * @throws GetresponseApiException
     */
    private function createProductVariant($grShopId, $grProductId, Product $product)
    {
        $productVariant = $product->getVariant();

        $grVariant = $this->getresponseApi->createProductVariant(
            $grShopId,
            $grProductId,
            $productVariant->toRequestArray()
        );

        $grVariantId = $grVariant['variantId'];

        $this->dbRepository->saveProductMapping(
            new ProductMapping(
                $product->getExternalId(),
                $productVariant->getExternalId(),
                $grShopId,
                $grProductId,
                $grVariantId
            )
        );

        return $productVariant->toRequestArrayWithVariantId($grVariantId);
    }

}