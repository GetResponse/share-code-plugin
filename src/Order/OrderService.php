<?php
namespace GrShareCode\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;

/**
 * Class OrderService
 * @package GrShareCode\Order
 */
class OrderService
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
     * @param AddOrderCommand $command
     * @throws \GrShareCode\Cart\ContactNotFoundException
     */
    public function sendOrder(AddOrderCommand $command)
    {
        $contact = $this->getresponseApi->getContactByEmail($command->getEmail(), $command->getListId());

        $variants = [];
        foreach ($command->getProducts() as $product) {
            $variantId = $this->dbRepository->getProductVariantById($command->getShopId(), $product->getId());

            if (empty($variantId)) {
                $this->getresponseApi->createProduct($command->getShopId(), $product);
                $this->dbRepository->saveProductVariant($command->getShopId(), $product->getId(), $product->getGrVariantId());

                $variants[] = [
                    'variantId' => $product->getGrVariantId(),
                    'quantity'  => $product->getQuantity(),
                    'price'     => $product->getPrice(),
                    'priceTax'  => $product->getPriceTax(),
                ];
            }
        }

        $grOrder = [
            'contactId' => $contact['contactId'],
            'totalPrice' => $command->getTotalPrice(),
            'totalPriceTax' => $command->getTotalPriceTax(),
            'orderUrl' => $command->getOrderUrl(),
            'externalId' => $command->getOrderId(),
            'currency' => $command->getCurrency(),
            'status' => $command->getStatus(),
            'cartId' => $command->getCartId(),
            'description' => $command->getDescription(),
            'shippingPrice' => $command->getShippingPrice(),
            'billingPrice' => $command->getBillingPrice(),
            'processedAt' => $command->getProcessedAt(),
            'shippingAddress' => $command->getShippingAddress(),
            'billingAddress' => $command->getBillingAddress(),
            'selectedVariants' => $variants
        ];

        $grOrderId = $this->dbRepository->getGrOrderIdFromMapping($command->getShopId(), $command->getOrderId());

        if (empty($grOrderId)) {
            $grOrderId = $this->getresponseApi->createOrder($command->getShopId(), $grOrder);
            $this->dbRepository->saveOrderMapping($command->getShopId(), $command->getOrderId(), $grOrderId);
            $this->getresponseApi->removeCart($command->getShopId(), $command->getCartId());
        } else {
            $this->getresponseApi->updateOrder($command->getShopId(), $grOrderId, $grOrder);
        }
    }
}
