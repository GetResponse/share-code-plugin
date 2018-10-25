<?php

namespace GrShareCode\Order;


use GrShareCode\Address\Address;

class OrderPayloadFactory
{
    /**
     * @param Order $order
     * @param array $variants
     * @param null|string $contactId
     * @param null|string $cartId
     * @return array
     */
    public function create(Order $order, array $variants, $contactId = null, $cartId = null)
    {
        $orderPayload = [
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
            $orderPayload['shippingAddress'] = $this->buildAddress($order->getShippingAddress());
        }

        if (!empty($contactId)) {
            $orderPayload['contactId'] = $contactId;
        }

        if (!empty($cartId)) {
            $orderPayload['cartId'] = $cartId;
        }

        return $orderPayload;
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