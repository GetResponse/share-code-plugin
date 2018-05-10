<?php
namespace GrShareCode\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductVariant;

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
     * @throws GetresponseApiException
     */
    public function sendOrder(AddOrderCommand $command)
    {
        $contact = $this->getresponseApi->getContactByEmail($command->getEmail(), $command->getListId());

        $variants = [];

        /** @var Product $product */
        foreach ($command->getProducts() as $product) {

            $productVariant = $product->getVariant();

            $grVariant = $this->dbRepository->getProductVariantById(
                $command->getShopId(),
                $productVariant->getId(),
                $product->getId()
            );

            if (empty($grVariant)) {

                    $variant = [
                        'name'     => $productVariant->getName(),
                        'price'    => $productVariant->getPrice(),
                        'priceTax' => $productVariant->getPriceTax(),
                        'sku'      => $productVariant->getSku(),
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

                    $grVariant = $this->getresponseApi->createProductVariant(
                        $command->getShopId(),
                        $grProduct['productId'], $variant
                    );

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

        $grOrder = [
            'contactId'        => $contact['contactId'],
            'totalPrice'       => $command->getTotalPrice(),
            'totalPriceTax'    => $command->getTotalPriceTax(),
            'orderUrl'         => $command->getOrderUrl(),
            'externalId'       => $command->getOrderId(),
            'currency'         => $command->getCurrency(),
            'status'           => $command->getStatus(),
            'cartId'           => $command->getCartId(),
            'description'      => $command->getDescription(),
            'shippingPrice'    => $command->getShippingPrice(),
            'billingPrice'     => $command->getBillingPrice(),
            'processedAt'      => $command->getProcessedAt(),
            'shippingAddress'  => $command->getShippingAddress(),
            'billingAddress'   => $command->getBillingAddress(),
            'selectedVariants' => $variants,
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
