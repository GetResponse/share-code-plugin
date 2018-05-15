<?php
namespace GrShareCode\Cart;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductVariant;

/**
 * Class CartService
 * @package GrShareCode\Cart
 */
class CartService
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
     * @param AddCartCommand $command
     * @throws GetresponseApiException
     */
    public function sendCart(AddCartCommand $command)
    {
        $contact = $this->getresponseApi->getContactByEmail($command->getEmail(), $command->getListId());

        if (empty($contact)) {
            return;
        }

        $variants = [];
        /** @var Product $product */
        foreach ($command->getProducts() as $product) {

            /** @var ProductVariant $productVariant */
            foreach ($product->getProductVariants() as $productVariant) {

                $variant = [
                    'name' => $productVariant->getName(),
                    'price' => $productVariant->getPrice(),
                    'priceTax' => $productVariant->getPriceTax(),
                    'quantity' => $productVariant->getQuantity(),
                    'sku' => $productVariant->getSku()
                ];

                $grVariant = $this->dbRepository->getProductVariantById($command->getShopId(), $productVariant->getExternalId(), $product->getExternalId());

                if (empty($grVariant)) {

                    $grProduct = $this->dbRepository->getProductById($command->getShopId(), $product->getExternalId());

                    if (empty($grProduct)) {

                        $productCategories = [];

                        $grProductParams = [
                            'name' => $product->getName(),
                            'url' => $product->getUrl(),
                            'type' => $product->getType(),
                            'vendor' => $product->getVendor(),
                            'externalId' => $product->getExternalId(),
                            'categories' => $productCategories,
                            'variants' => [$variant],
                        ];

                        $grProduct = $this->getresponseApi->createProduct($command->getShopId(), $grProductParams);
                        $this->dbRepository->saveProductMapping(
                            $command->getShopId(),
                            $product->getExternalId(),
                            $productVariant->getExternalId(),
                            $grProduct['productId'],
                            $grProduct['variants'][0]['variantId']
                        );

                        $grVariant = $grProduct['variants'][0]['variantId'];
                    } else {

                        $grVariant = $this->getresponseApi->createProductVariant($command->getShopId(), $grProduct['productId'], $variant);

                        $this->dbRepository->saveProductMapping(
                            $command->getShopId(),
                            $product->getExternalId(),
                            $productVariant->getExternalId(),
                            $grProduct['productId'],
                            $grVariant['variantId']
                        );

                        $grVariant = $grVariant['variantId'];

                    }
                }

                $variant['variantId'] = $grVariant;
                $variants[] = $variant;
            }
        }

        $grCart = [
            'contactId'        => $contact['contactId'],
            'totalPrice'       => $command->getTotalPrice(),
            'totalTaxPrice'    => $command->getTotalTaxPrice(),
            'currency'         => $command->getCurrency(),
            'selectedVariants' => $variants,
            'externalId'       => $command->getCartId(),
            'cartUrl'          => $command->getCartUrl()

        ];

        $grCartId = $this->dbRepository->getGrCartIdFromMapping($command->getShopId(), $command->getCartId());

        if (empty($grCartId)) {
            $grCart = $this->getresponseApi->createCart($command->getShopId(), $grCart);

            $this->dbRepository->saveCartMapping($command->getShopId(), $command->getCartId(), $grCart['cartId']);
        } else {
            $this->getresponseApi->updateCart($command->getShopId(), $grCartId, $grCart);
        }
    }
}
