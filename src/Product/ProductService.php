<?php
namespace GrShareCode\Product;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\Category\Category;
use GrShareCode\Product\Category\CategoryCollection;
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

                $variants[] = [
                    'variantId' => $productMapping->getGrVariantId(),
                    'price' => $productVariant->getPrice(),
                    'priceTax' => $productVariant->getPriceTax(),
                    'quantity' => $productVariant->getQuantity(),
                ];

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

        $variant = [
            'name' => $productVariant->getName(),
            'price' => $productVariant->getPrice(),
            'priceTax' => $productVariant->getPriceTax(),
            'sku' => $productVariant->getSku()
        ];

        $grProductParams = [
            'name' => $product->getName(),
            'categories' => $this->getCategories($product->getCategories()),
            'variants' => [$variant],
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

        return [
            'variantId' => $grVariantId,
            'price' => $productVariant->getPrice(),
            'priceTax' => $productVariant->getPriceTax(),
            'quantity' => $productVariant->getQuantity()
        ];
    }

    /**
     * @param CategoryCollection $categoriesCollection
     * @return array
     */
    private function getCategories(CategoryCollection $categoriesCollection)
    {
        $categories = [];

        /** @var Category $category */
        foreach ($categoriesCollection as $category) {

            $categories[] = [
                'name' => $category->getName(),
                'parentId' => $category->getName(),
                'externalId' => $category->getExternalId(),
                'url' => $category->getUrl(),
            ];
        }

        return $categories;
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

        $variant = [
            'name' => $productVariant->getName(),
            'price' => $productVariant->getPrice(),
            'priceTax' => $productVariant->getPriceTax(),
            'sku' => $productVariant->getSku()
        ];

        $grVariant = $this->getresponseApi->createProductVariant($grShopId, $grProductId, $variant);

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

        return [
            'variantId' => $grVariantId,
            'price' => $productVariant->getPrice(),
            'priceTax' => $productVariant->getPriceTax(),
            'quantity' => $productVariant->getQuantity()
        ];
    }

}