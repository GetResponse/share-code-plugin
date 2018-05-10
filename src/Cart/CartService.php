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

                $grVariant = $this->dbRepository->getProductVariantById($command->getShopId(), $productVariant->getId(), $product->getId());

                if (empty($grVariant)) {

                    $variant = [
                        'name' => $productVariant->getName(),
                        'price' => $productVariant->getPrice(),
                        'priceTax' => $productVariant->getPriceTax(),
                        'sku' => $productVariant->getSku()
                    ];

                    $grProduct = $this->dbRepository->getProductById($command->getShopId(), $product->getId());

                    if (empty($grProduct)) {

                        $grProductParams = [
                            'name' => $product->getName(),
                            'variants' => [$variant],
                        ];

                        $grProduct = $this->getresponseApi->createProduct($command->getShopId(), $grProductParams);
                        $this->dbRepository->saveProductMapping(
                            $command->getShopId(),
                            $product->getId(),
                            $productVariant->getId(),
                            $grProduct['productId'],
                            $grProduct['variants'][0]['variantId']
                        );

                    } else {

                        $grVariant = $this->getresponseApi->createProductVariant($command->getShopId(), $grProduct['productId'], $variant);

                        $this->dbRepository->saveProductMapping(
                            $command->getShopId(),
                            $product->getId(),
                            $productVariant->getId(),
                            $grProduct['productId'],
                            $grVariant['variantId']
                        );
                    }

                    $variants[] = $variant;
                }
            }
        }

        $grCart = [
            'contactId'        => $contact['contactId'],
            'currency'         => $command->getCurrency(),
            'totalPrice'       => $command->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId'       => $command->getCartId(),
            'totalTaxPrice'    => $command->getTotalTaxPrice(),
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
