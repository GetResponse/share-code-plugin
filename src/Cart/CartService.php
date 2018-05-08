<?php
namespace GrShareCode\Cart;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\ProductsCollection;

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
     * @param string $apiKey
     * @param GetresponseApi $getresponseApi
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct(GetresponseApi $getresponseApi, DbRepositoryInterface $dbRepository)
    {
        // stworzenie obiektu API w oparciu o Api Key
        $this->getresponseApi = $getresponseApi;
        $this->dbRepository = $dbRepository;
    }

    /**
     * @param AddCartCommand $command
     * @throws ContactNotFoundException
     * @throws GetresponseApiException
     */
    public function sendCart(AddCartCommand $command)
    {
        $contact = $this->getresponseApi->getContactByEmail($command->getEmail(), $command->getListId());

        $variants = [];
        foreach ($command->getProducts() as $product) {
            $variantId = $this->dbRepository->getProductVariantById($product->getId());

            if (empty($variantId)) {
                $this->getresponseApi->createProduct($product);
                $this->dbRepository->saveProductVariant($product->getId(), $product->getGrVariantId());

                $variants[] = [
                    'variantId' => $product->getGrVariantId(),
                    'quantity'  => $product->getQuantity(),
                    'price'     => $product->getPrice(),
                    'priceTax'  => $product->getPriceTax(),
                ];
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

        if (empty($command->getGrCartId())) {
            $cartId = $this->getresponseApi->createCart($grCart);
            $command->setGrCartId($cartId);

            $this->dbRepository->saveCartMapping($cartId, $cartId);
        } else {
            $this->getresponseApi->updateCart($grCart);
        }
    }
}
