<?php
namespace GrShareCode\Product;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\Variant\Variant;
use GrShareCode\ProductMapping\ProductMapping;

/**
 * Class ProductService
 * @package GrShareCode\Product
 */
class ProductService
{
    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository)
    {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->dbRepository = $dbRepository;
    }

    /**
     * @param ProductsCollection $products
     * @param string $grShopId
     * @return array
     * @throws GetresponseApiException
     */
    public function getProductsVariants(ProductsCollection $products, $grShopId)
    {
        $variants = [];

        /** @var Product $product */
        foreach ($products as $product) {

            $productMapping = $this->dbRepository->getProductMappingByProductId($grShopId, $product->getExternalId());

            if (!$productMapping->productExistsInGr()) {
                $this->createProductWithVariants($grShopId, $product);
                $productMapping = $this->dbRepository->getProductMappingByProductId($grShopId, $product->getExternalId());
            }

            $productId = $productMapping->getGrProductId();
            $productVariants = $product->getVariants();

            /** @var Variant $productVariant */
            foreach ($productVariants as $productVariant) {

                $productMapping = $this->dbRepository->getProductMappingByVariantId(
                    $grShopId,
                    $product->getExternalId(),
                    $productVariant->getExternalId()
                );

                if ($productMapping->variantExistsInGr()) {
                    $variants[] = $productVariant->toRequestArrayWithVariantId($productMapping->getGrVariantId());
                    continue;
                }

                $variant = $this->createProductVariant(
                    $grShopId,
                    $productId,
                    $product->getExternalId(),
                    $productVariant);

                $variants[] = $variant;
            }
        }

        return $variants;
    }

    /**
     * @param string $grShopId
     * @param Product $product
     * @throws GetresponseApiException
     */
    private function createProductWithVariants($grShopId, Product $product)
    {
        $productVariants = $product->getVariants();

        $grProductParams = [
            'name' => $product->getName(),
            'url' => $product->getUrl(),
            'externalId' => $product->getExternalId(),
            'categories' => $product->getCategories()->toRequestArray(),
            'variants' => $productVariants->toRequestArray(),
        ];

        $grProduct = $this->getresponseApiClient->createProduct($grShopId, $grProductParams);

        foreach ($grProduct['variants'] as $variant) {

            $this->dbRepository->saveProductMapping(
                new ProductMapping(
                    $product->getExternalId(),
                    $variant['externalId'],
                    $grShopId,
                    $grProduct['productId'],
                    $variant['variantId']
                )
            );
        }
    }

    /**
     * @param string $grShopId
     * @param string $grProductId
     * @param $externalProductId
     * @param Variant $productVariant
     * @return array
     * @throws GetresponseApiException
     */
    private function createProductVariant($grShopId, $grProductId, $externalProductId, Variant $productVariant)
    {
        $grVariant = $this->getresponseApiClient->createProductVariant(
            $grShopId,
            $grProductId,
            $productVariant->toRequestArray()
        );

        $grVariantId = $grVariant['variantId'];

        $this->dbRepository->saveProductMapping(
            new ProductMapping(
                $externalProductId,
                $productVariant->getExternalId(),
                $grShopId,
                $grProductId,
                $grVariantId
            )
        );

        return $productVariant->toRequestArrayWithVariantId($grVariantId);
    }

}