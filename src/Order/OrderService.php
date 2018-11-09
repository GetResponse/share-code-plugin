<?php
namespace GrShareCode\Order;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Api\Exception\GetresponseApiException;
use GrShareCode\Order\Command\AddOrderCommand;
use GrShareCode\Order\Command\EditOrderCommand;
use GrShareCode\Product\ProductService;

/**
 * Class OrderService
 * @package GrShareCode\Order
 */
class OrderService
{
    /** @var GetresponseApiClient */
    private $getresponseApiClient;
    /** @var DbRepositoryInterface */
    private $dbRepository;
    /** @var ProductService */
    private $productService;
    /** @var OrderPayloadFactory */
    private $orderPayloadFactory;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @param ProductService $productService
     * @param OrderPayloadFactory $orderPayloadFactory
     */
    public function __construct(
        GetresponseApiClient $getresponseApiClient,
        DbRepositoryInterface $dbRepository,
        ProductService $productService,
        OrderPayloadFactory $orderPayloadFactory
    ) {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->dbRepository = $dbRepository;
        $this->productService = $productService;
        $this->orderPayloadFactory = $orderPayloadFactory;
    }

    /**
     * @param AddOrderCommand $addOrderCommand
     * @throws GetresponseApiException
     */
    public function addOrder(AddOrderCommand $addOrderCommand)
    {
        $contact = $this->getresponseApiClient->findContactByEmailAndListId(
            $addOrderCommand->getEmail(),
            $addOrderCommand->getContactListId()
        );

        if (empty($contact)) {
            return;
        }

        $order = $addOrderCommand->getOrder();

        $grOrderId = $this->dbRepository->getGrOrderIdFromMapping(
            $addOrderCommand->getShopId(),
            $order->getExternalOrderId()
        );

        // Order already sent, cannot add it again
        if (!empty($grOrderId)) {
            return;
        }

        $orderPayload = $this->orderPayloadFactory->create(
            $order,
            $this->productService->getProductsVariants(
                $order->getProducts(),
                $addOrderCommand->getShopId()
            ),
            $contact['contactId'],
            $this->dbRepository->getGrCartIdFromMapping(
                $addOrderCommand->getShopId(),
                $order->getExternalCartId()
            )
        );

        $grOrderId = $this->getresponseApiClient->createOrder(
            $addOrderCommand->getShopId(),
            $orderPayload,
            $addOrderCommand->skipAutomation()
        );

        if (isset($orderPayload['cartId'])) {
            $this->getresponseApiClient->removeCart($addOrderCommand->getShopId(), $orderPayload['cartId']);
        }

        $this->dbRepository->saveOrderMapping(
            $addOrderCommand->getShopId(),
            $order->getExternalOrderId(),
            $grOrderId,
            $this->getOrderPayloadHash($orderPayload)
        );
    }

    /**
     * @param EditOrderCommand $editOrderCommand
     * @throws GetresponseApiException
     */
    public function updateOrder(EditOrderCommand $editOrderCommand)
    {
        $order = $editOrderCommand->getOrder();

        $orderPayload = $this->orderPayloadFactory->create(
            $order,
            $this->productService->getProductsVariants(
                $order->getProducts(),
                $editOrderCommand->getShopId()
            )
        );

        $grOrderId = $this->dbRepository->getGrOrderIdFromMapping(
            $editOrderCommand->getShopId(),
            $order->getExternalOrderId()
        );

        if ($this->hasPayloadChanged($orderPayload, $editOrderCommand->getShopId(), $order->getExternalOrderId())) {
            return;
        }

        $this->getresponseApiClient->updateOrder(
            $editOrderCommand->getShopId(),
            $grOrderId,
            $orderPayload
        );

    }

    /**
     * @param array $orderPayload
     * @return string
     */
    private function getOrderPayloadHash(array $orderPayload)
    {
        if (isset($orderPayload['contactId'])) {
            unset($orderPayload['contactId']);
        }

        if (isset($orderPayload['cartId'])) {
            unset($orderPayload['cartId']);
        }

        return md5(json_encode($orderPayload));
    }

    /**
     * @param array $orderPayload
     * @param string $shopId
     * @param int $externalOrderId
     * @return bool
     */
    private function hasPayloadChanged(array $orderPayload, $shopId, $externalOrderId)
    {
        $oldPayloadHash = $this->dbRepository->getPayloadMd5FromOrderMapping(
            $shopId,
            $externalOrderId
        );

        return $this->getOrderPayloadHash($orderPayload) == $oldPayloadHash;
    }
}
