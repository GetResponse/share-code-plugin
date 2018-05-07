<?php
namespace ShareCode\Cart;

use ShareCode\GetresponseApi;
use ShareCode\GetresponseApiException;

/**
 * Class CartService
 * @package ShareCode\Cart
 */
class CartService
{
    /** @var GetresponseApi */
    private $getresponseApi;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /**
     * @param string $apiKey
     * @param DbRepositoryInterface $dbRepository
     */
    public function __construct($apiKey, DbRepositoryInterface $dbRepository)
    {
        // stworzenie obiektu API w oparciu o Api Key
        $this->getresponseApi = new GetresponseApi($apiKey);
        $this->dbRepository = $dbRepository;
    }

    /**
     * @param CartCommand $command
     * @return string
     * @throws SubscriberNotFoundException
     * @throws GetresponseApiException
     */
    public function sendCart(CartCommand $command)
    {
        $subscriber = $this->getresponseApi->getSubscriberByEmail($command->getEmail(), $command->getCampaignId());

        if (empty($subscriber)) {
            throw new SubscriberNotFoundException();
        }

        $variants = [];
        /** @var Product $product */
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
            'contactId' => $subscriber['contactId'],
            'currency' => $command->getCurrency(),
            'totalPrice' => $command->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId' => $command->getCartId(),
            'totalTaxPrice' => $command->getTotalTaxPrice()
        ];

        if (empty($command->getGrCartId())) {
            $cartId = $this->getresponseApi->createCart($grCart);
            $command->setGrCartId($cartId);
        }

        $this->getresponseApi->updateCart($grCart);

        return $command->getGrCartId();
    }
}
