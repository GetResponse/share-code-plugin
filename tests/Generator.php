<?php
namespace GrShareCode\Tests;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Product\Category\Category;
use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Category\CategoryException;
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
     * @throws CategoryException
     */
    public static function createProductsCollection()
    {
        $categoryCollection = new CategoryCollection();
        $categoryCollection->add(new Category('t-shirts'));
        $product = new Product(1, 'simple product', self::createProductVariant(), $categoryCollection);
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
        return new Variant(1, 'simple product', 9.99, 12.00, 'simple-product', 1);
    }
}
