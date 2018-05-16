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
            'shippingAddress' => $this->buildAddress($order->getShippingAddress()),
            'billingAddress' => $this->buildAddress($order->getBillingAddress()),
            'selectedVariants' => $variants,
        ];

        $grOrderId = $this->dbRepository->getGrOrderIdFromMapping($addOrderCommand->getShopId(), $order->getOrderId());

        if (empty($grOrderId)) {

            $grOrderId = $this->getresponseApi->createOrder(
                $addOrderCommand->getShopId(),
                $grOrder,
                $addOrderCommand->skipAutomation()
            );

            $this->dbRepository->saveOrderMapping($addOrderCommand->getShopId(), $order->getOrderId(), $grOrderId);
            $this->getresponseApi->removeCart($addOrderCommand->getShopId(), $order->getCartId());

        } else {
            $this->getresponseApi->updateOrder(
                $addOrderCommand->getShopId(),
                $grOrderId,
                $grOrder,
                $addOrderCommand->skipAutomation()
            );
        }
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
}
