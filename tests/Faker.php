<?php
namespace GrShareCode\Tests;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Product\ProductVariant;
use GrShareCode\Product\ProductVariantsCollection;

/**
 * Class Faker
 * @package GrShareCode\Tests
 */
class Faker
{
    /**
     * @return ProductVariantsCollection
     */
    public static function createProductVariantsCollection()
    {
        $collection = new ProductVariantsCollection();
        $collection->add(new ProductVariant(1, 'simple product', 9.99, 12.00, 'simple-product'));

        return $collection;
    }

    /**
     * @return ProductsCollection
     */
    public static function createProductsCollection()
    {
        $product = new Product(1, 'simple product', self::createProductVariantsCollection());
        $products = new ProductsCollection();
        $products->add($product);

        return $products;
    }

    /**
     * @return AddCartCommand
     */
    public static function createAddCartCommand()
    {
        $products = Faker::createProductsCollection();

        return new AddCartCommand('simple@example.com', 'shopId', 'listId', $products, 'cartId', 'currency', 9.99,
            12.00);
    }
}
