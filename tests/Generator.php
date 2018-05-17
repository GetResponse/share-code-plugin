<?php
namespace GrShareCode\Tests;

use GrShareCode\Address\Address;
use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\Cart;
use GrShareCode\Order\AddOrderCommand;
use GrShareCode\Order\Order;
use GrShareCode\Product\Category\Category;
use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Product\Variant\Images\Image;
use GrShareCode\Product\Variant\Images\ImagesCollection;
use GrShareCode\Product\Variant\Variant;

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

    public static function createAddOrderCommand()
    {
        $order = new Order(
            21,
            self::createProductsCollection(),
            20.00,
            25.00,
            'http://getresponse.com',
            'PLN',
            'pending',
            431,
            'This is description',
            3.53,
            'awaiting',
            '2018-05-17T16:15:33+0200',
            self::createAddress(),
            self::createAddress()
        );

        return new AddOrderCommand(
            $order,
            'simple@example.com',
            'listId',
            'shopId'
        );
    }

    /**
     * @return Address
     */
    public static function createAddress()
    {
        return (new Address('POL', 'Poland'))
            ->setFirstName('Adam')
            ->setLastName('Kowalski')
            ->setAddress1('Address number 1')
            ->setAddress2('Address number 2')
            ->setCity('Gdynia')
            ->setZip('81-102')
            ->setProvince('Pomorskie')
            ->setProvinceCode('AASDMEF2')
            ->setPhone('48-123-321-123')
            ->setCompany('GetResponse Company');
    }
}
