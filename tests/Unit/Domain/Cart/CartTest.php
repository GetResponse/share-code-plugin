<?php
namespace GrShareCode\Tests\Unit\Domain\Cart;

use GrShareCode\Cart\Cart;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Tests\Unit\BaseTestCase;
use GrShareCode\Validation\Assert\InvalidArgumentException;

/**
 * Class CartTest
 */
class CartTest extends BaseTestCase
{

    /**
     * @test
     */
    public function shouldCreateValidCart()
    {
        $cartId = 'x3Df';
        $currency = 'PLN';
        $totalPrice = 9.99;
        $totalTaxPrice = 0.0;
        $cartUrl = 'http://site.com';

        $cart = new Cart(
            $cartId,
            new ProductsCollection(),
            $currency,
            $totalPrice,
            $totalTaxPrice,
            $cartUrl
        );

        self::assertEquals($cartId, $cart->getCartId());
        self::assertEquals($currency, $cart->getCurrency());
        self::assertEquals($totalPrice, $cart->getTotalPrice());
        self::assertEquals($totalTaxPrice, $cart->getTotalTaxPrice());
        self::assertEquals($cartUrl, $cart->getCartUrl());
    }

    /**
     * @test
     * @dataProvider invalidCartProvider
     * @param $cartId
     * @param $currency
     * @param $totalPrice
     * @param $totalTaxPrice
     * @param $cartUrl
     */
    public function shouldThrowExceptionWhenInvalidCart($cartId, $currency, $totalPrice, $totalTaxPrice, $cartUrl)
    {
        $this->expectException(InvalidArgumentException::class);

        new Cart(
            $cartId,
            new ProductsCollection(),
            $currency,
            $totalPrice,
            $totalTaxPrice,
            $cartUrl
        );
    }

    public function invalidCartProvider()
    {
        return [
            'emptyCartId' => [
                'cartId' => '',
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'nullCartId' => [
                'cartId' => null,
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'emptyCurrency' => [
                'cartId' => 'X3d',
                'currency' => '',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'nullCurrency' => [
                'cartId' => 'X3d',
                'currency' => null,
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'incorrectCurrency' => [
                'cartId' => 'X3d',
                'currency' => 'PELEENY',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'emptyTotalPrice' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => '',
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'nullTotalPrice' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => null,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'zeroTotalPrice' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => 0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'invalidTotalPrice' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => '10.0',
                'totalTaxPrice' => 1.0,
                'cartUrl' => 'http://site.com'
            ],
            'emptyTotalTaxPrice' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => '',
                'cartUrl' => 'http://site.com'
            ],
            'invalidTotalTaxPrice' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => '1.0',
                'cartUrl' => 'http://site.com'
            ],
            'emptyCartUrl' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => ''
            ],
            'nullCartUrl' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => null
            ],
            'invalidCartUrl' => [
                'cartId' => 'X3d',
                'currency' => 'PLN',
                'totalPrice' => 10.0,
                'totalTaxPrice' => 1.0,
                'cartUrl' => 3.33
            ]
        ];
    }
}
