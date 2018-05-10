<?php
namespace GrShareCode\Tests;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductException;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Product\Variant\Variant;
use GrShareCode\Product\Variant\VariantException;

/**
 * Class Faker
 * @package GrShareCode\Tests
 */
class Generator
{
    /**
     * @return AddCartCommand
     */
    public static function createAddCartCommand()
    {
        $products = self::createProductsCollection();

        return new AddCartCommand(
            'simple@example.com',
            'shopId',
            'listId',
            $products,
            'cartId',
            'currency',
            9.99,
            12.00
        );
    }

    /**
     * @return ProductsCollection
     * @throws VariantException
     * @throws ProductException
     */
    public static function createProductsCollection()
    {
        $product = new Product(1, 'simple product', self::createProductVariant());
        $products = new ProductsCollection();
        $products->add($product);

        return $products;
    }

    /**
     * @return Variant
     * @throws VariantException
     */
    public static function createProductVariant()
    {
        return new Variant(1, 'simple product', 9.99, 12.00, 'simple-product');
    }
}
