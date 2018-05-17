<?php
namespace GrShareCode\Tests;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\Cart;
use GrShareCode\Product\Category\Category;
use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Category\CategoryException;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductException;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Product\Variant\Images\Image;
use GrShareCode\Product\Variant\Images\ImagesCollection;
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
        $cart = new Cart(1, $products, 'PLN', 10.00, 123.3);

        return new AddCartCommand(
            $cart,
            'simple@example.com',
            'listId',
            'shopId'
        );
    }

    /**
     * @return ProductsCollection
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
     */
    public static function createProductVariant()
    {
        $imageCollection = new ImagesCollection();
        $imageCollection->add(new Image('https://getresponse.com', 1));

        $productVariant = new Variant(
            1,
            'simple product',
            9.99,
            12.00,
            'simple-product'
        );

        return $productVariant
            ->setQuantity(1)
            ->setUrl('https://getresponse.com')
            ->setDescription('This is description')
            ->setImages($imageCollection);
    }
}
