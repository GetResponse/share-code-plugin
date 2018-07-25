<?php
namespace GrShareCode\Order;

use GrShareCode\Address\Address;
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

    /** @var ProductService */
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
        $contact = $this->getresponseApi->getContactByEmail(
            $addOrderCommand->getEmail(),
            $addOrderCommand->getContactListId()
        );

        if (empty($contact)) {
            return;
        }

        $order = $addOrderCommand->getOrder();

        $variants = $this->productService->getProductsVariants($order->getProducts(), $addOrderCommand->getShopId());

        $grOrder = [
            'contactId' => $contact['contactId'],
            'totalPrice' => $order->getTotalPrice(),
            'totalPriceTax' => $order->getTotalPriceTax(),
            'orderUrl' => $order->getOrderUrl(),
            'externalId' => $order->getExternalOrderId(),
            'currency' => $order->getCurrency(),
            'status' => $order->getStatus(),
            'description' => $order->getDescription(),
            'shippingPrice' => $order->getShippingPrice(),
            'billingPrice' => $order->getBillingStatus(),
            'processedAt' => $order->getProcessedAt(),
            'billingAddress' => $this->buildAddress($order->getBillingAddress()),
            'selectedVariants' => $variants,
        ];

        if ($order->hasShippingAddress()) {
            $grOrder['shippingAddress'] = $this->buildAddress($order->getShippingAddress());
        }

        $grOrderId = $this->dbRepository->getGrOrderIdFromMapping(
            $addOrderCommand->getShopId(),
            $order->getExternalOrderId()
        );

        $payloadMd5 = md5(json_encode($grOrder));

        if (empty($grOrderId)) {

            $cartId = $this->dbRepository->getGrCartIdFromMapping($addOrderCommand->getShopId(),
                $order->getExternalCartId());

            $grOrder['cartId'] = $cartId;

            $grOrderId = $this->getresponseApi->createOrder(
                $addOrderCommand->getShopId(),
                $grOrder,
                $addOrderCommand->skipAutomation()
            );

            $this->getresponseApi->removeCart($addOrderCommand->getShopId(), $cartId);

        } else {

            if ($this->hasPayloadChanged($addOrderCommand, $payloadMd5)) {
                return;
            }

            $this->getresponseApi->updateOrder(
                $addOrderCommand->getShopId(),
                $grOrderId,
                $grOrder,
                $addOrderCommand->skipAutomation()
            );
        }

        $this->dbRepository->saveOrderMapping(
            $addOrderCommand->getShopId(),
            $order->getExternalOrderId(),
            $grOrderId,
            $payloadMd5
        );
    }

    /**
     * @param Address $address
     * @return array
     */
    private function buildAddress(Address $address)
    {
        return [
            'countryCode' => $address->getCountryCode(),
            'countryName' => $address->getCountryName(),
            'name' => $address->getName(),
            'firstName' => $address->getFirstName(),
            'lastName' => $address->getLastName(),
            'address1' => $address->getAddress1(),
            'address2' => $address->getAddress2(),
            'city' => $address->getCity(),
            'zip' => $address->getZip(),
            'province' => $address->getProvince(),
            'provinceCode' => $address->getProvinceCode(),
            'phone' => $address->getPhone(),
            'company' => $address->getCompany()
        ];
    }

    /**
     * @param AddOrderCommand $addOrderCommand
     * @param string $newPayloadMd5
     * @return bool
     */
    private function hasPayloadChanged($addOrderCommand, $newPayloadMd5)
    {
        $oldPayloadMd5 = $this->dbRepository->getPayloadMd5FromOrderMapping(
            $addOrderCommand->getShopId(),
            $addOrderCommand->getOrder()->getExternalOrderId()
        );

        return $oldPayloadMd5 === $newPayloadMd5;
    }
}
