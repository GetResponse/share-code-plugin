<?php

namespace GrShareCode\Tests\Unit\Domain\Order;

use GrShareCode\Address\Address;
use GrShareCode\Order\Order;
use GrShareCode\Order\OrderPayloadFactory;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class OrderPayloadFactoryTest
 * @package GrShareCode\Tests\Unit\Domain\Order
 */
class OrderPayloadFactoryTest extends BaseTestCase
{

    /**
     * @test
     * @dataProvider orderPayloadFactoryProvider
     * @param Order $order
     * @param string $contactId
     * @param string $cartId
     * @param array $variants
     * @param array $expectedPayload
     */
    public function shouldOmitEmptyFields(Order $order, $contactId, $cartId, array $variants, array $expectedPayload)
    {
        self::assertEquals(
            $expectedPayload,
            (new OrderPayloadFactory())->create($order, $variants, $contactId, $cartId)
        );
    }

    /**
     * @return array
     */
    public function orderPayloadFactoryProvider()
    {
        $order1 = new Order(
            'ext_id',
            100.0,
            'PLN',
            new ProductsCollection()
        );

        $order2 = new Order(
            'ext_id',
            100.0,
            'PLN',
            new ProductsCollection()
        );

        $order2->setBillingAddress(
            new Address('POL', 'name of address')
        );

        return [
            [
                'order' => $order1,
                'contactId' => 'c_id',
                'cartId' => null,
                'variants' => [],
                'expected' => [
                    'totalPrice' => 100.0,
                    'currency' => 'PLN',
                    'externalId' => 'ext_id',
                    'selectedVariants' => [],
                    'contactId' => 'c_id',
                ]
            ],
            [
                'order' => $order1,
                'contactId' => null,
                'cartId' => null,
                'variants' => [],
                'expected' => [
                    'totalPrice' => 100.0,
                    'currency' => 'PLN',
                    'externalId' => 'ext_id',
                    'selectedVariants' => []
                ]
            ],
            [
                'order' => $order2,
                'contactId' => 'contact_id',
                'cartId' => 'cart_id',
                'variants' => [],
                'expected' => [
                    'totalPrice' => 100.0,
                    'currency' => 'PLN',
                    'externalId' => 'ext_id',
                    'selectedVariants' => [],
                    'billingAddress' => [
                        'countryCode' => 'POL',
                        'name' => 'name of address'
                    ],
                    'contactId' => 'contact_id',
                    'cartId' => 'cart_id',
                ]
            ]
        ];
    }

}
