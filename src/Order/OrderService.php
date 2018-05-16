<?php
namespace GrShareCode\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\ProductService;

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
     * @var ProductService
     */
    private $productService;

    /**
     * @param GetresponseApi $getresponseApi
     * @param DbRepositoryInterface $dbRepository
     * @param ProductService $productService
     */
    public function __construct(
        GetresponseApi $getresponseApi,
        DbRepositoryInterface $dbRepository,
        ProductService $productService
    ) {
        $this->getresponseApi = $getresponseApi;
        $this->dbRepository = $dbRepository;
        $this->productService = $productService;
    }

    /**
     * @param AddOrderCommand $addOrderCommand
     * @throws GetresponseApiException
     */
    public function sendOrder(AddOrderCommand $addOrderCommand)
    {

//        $this->validator-
        // @todo: add Validator
        $contact = $this->getresponseApi->getContactByEmail(
            $addOrderCommand->getEmail(),
            $addOrderCommand->getContactListId()
        );

        if (empty($contact)) {
            return;
        }

        $order = $addOrderCommand->getOrder();

        $variants = $this->productService->getProductVariants($order->getProducts(), $addOrderCommand->getShopId());

        $grOrder = [
            'contactId' => $contact['contactId'],
            'totalPrice' => $order->getTotalPrice(),
            'totalPriceTax' => $order->getTotalPriceTax(),
            'orderUrl' => $order->getOrderUrl(),
            'externalId' => $order->getOrderId(),
            'currency' => $order->getCurrency(),
            'status' => $order->getStatus(),
            'cartId' => $order->getCartId(),
            'description' => $order->getDescription(),
            'shippingPrice' => $order->getShippingPrice(),
            'billingPrice' => $order->getBillingPrice(),
            'processedAt' => $order->getProcessedAt(),
            'shippingAddress' => $order->getShippingAddress(),
            'billingAddress' => $order->getBillingAddress(),
            'selectedVariants' => $variants,
        ];

        $grOrderId = $this->dbRepository->getGrOrderIdFromMapping($addOrderCommand->getShopId(), $order->getOrderId());

        if (empty($grOrderId)) {
            $grOrderId = $this->getresponseApi->createOrder($addOrderCommand->getShopId(), $grOrder);
            $this->dbRepository->saveOrderMapping($addOrderCommand->getShopId(), $order->getOrderId(), $grOrderId);
            $this->getresponseApi->removeCart($addOrderCommand->getShopId(), $order->getCartId());
        } else {
            $this->getresponseApi->updateOrder($addOrderCommand->getShopId(), $grOrderId, $grOrder);
        }
    }
}
