<?php

namespace GrShareCode\Order;

use GrShareCode\Address\Address;

/**
 * Class OrderPayloadFactory
 * @package GrShareCode\Order
 */
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
            'currency' => $order->getCurrency(),
            'externalId' => $order->getExternalOrderId(),
            'selectedVariants' => $variants,
        ];

        if (null !== $order->getTotalPriceTax()) {
            $orderPayload['totalPriceTax'] = $order->getTotalPriceTax();
        }

        if (null !== $order->getOrderUrl()) {
            $orderPayload['orderUrl'] = $order->getOrderUrl();
        }

        if (null !== $order->getStatus()) {
            $orderPayload['status'] = $order->getStatus();
        }

        if (null !== $order->getDescription()) {
            $orderPayload['description'] = $order->getDescription();
        }

        if (null !== $order->getShippingPrice()) {
            $orderPayload['shippingPrice'] = $order->getShippingPrice();
        }

        if (null !== $order->getBillingStatus()) {
            $orderPayload['billingStatus'] = $order->getBillingStatus();
        }

        if (null !== $order->getProcessedAt()) {
            $orderPayload['processedAt'] = $order->getProcessedAt();
        }

        if ($order->getBillingAddress() instanceof Address) {
            $orderPayload['billingAddress'] = $this->buildAddress($order->getBillingAddress());
        }

        if ($order->getShippingAddress() instanceof Address) {
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
        $addressPayload = [
            'countryCode' => $address->getCountryCode(),
            'name' => $address->getName(),
        ];

        if (null !== $address->getFirstName()) {
            $addressPayload['firstName'] = $address->getFirstName();
        }

        if (null !== $address->getLastName()) {
            $addressPayload['lastName'] = $address->getLastName();
        }

        if (null !== $address->getAddress1()) {
            $addressPayload['address1'] = $address->getAddress1();
        }

        if (null !== $address->getAddress2()) {
            $addressPayload['address2'] = $address->getAddress2();
        }

        if (null !== $address->getCity()) {
            $addressPayload['city'] = $address->getCity();
        }

        if (null !== $address->getZip()) {
            $addressPayload['zip'] = $address->getZip();
        }

        if (null !== $address->getProvince()) {
            $addressPayload['province'] = $address->getProvince();
        }

        if (null !== $address->getProvinceCode()) {
            $addressPayload['provinceCode'] = $address->getProvinceCode();
        }

        if (null !== $address->getPhone()) {
            $addressPayload['phone'] = $address->getPhone();
        }

        if (null !== $address->getCompany()) {
            $addressPayload['company'] = $address->getCompany();
        }

        return $addressPayload;
    }
}