<?php
namespace GrShareCode\Tests;

use GrShareCode\Address\Address;
use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\Cart;
use GrShareCode\Contact\AddContactCommand;
use GrShareCode\Contact\ContactCustomField;
use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Export\ExportContactCommand;
use GrShareCode\Export\HistoricalOrder\HistoricalOrder;
use GrShareCode\Export\HistoricalOrder\HistoricalOrderCollection;
use GrShareCode\Export\Settings\ExportSettings;
use GrShareCode\Order\AddOrderCommand;
use GrShareCode\Order\Order;
use GrShareCode\Product\Category\Category;
use GrShareCode\Product\Category\CategoryCollection;
use GrShareCode\Product\Product;
use GrShareCode\Product\ProductsCollection;
use GrShareCode\Product\Variant\Images\Image;
use GrShareCode\Product\Variant\Images\ImagesCollection;
use GrShareCode\Product\Variant\Variant;
use GrShareCode\Product\Variant\VariantsCollection;
use GrShareCode\ProductMapping\ProductMapping;

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
        $cart = new Cart('100001', $products, 'PLN', 10.00, 123.3);

        return new AddCartCommand(
            $cart,
            'simple@example.com',
            'listId',
            'shopId'
        );
    }

    /**
     * @return AddCartCommand
     */
    public static function createAddCartCommandWithNoVariants()
    {
        $products = self::createProductsCollection(1,0);
        $cart = new Cart('100001', $products, 'PLN', 10.00, 123.3);

        return new AddCartCommand(
            $cart,
            'simple@example.com',
            'listId',
            'shopId'
        );
    }

    /**
     * @return CategoryCollection
     */
    public static function createCategoriesCollection()
    {
        $categoryCollection = new CategoryCollection();
        $categoryCollection->add(new Category('t-shirts'));
        return $categoryCollection;
    }

    /**
     * @param int $productsCount
     * @param int $variantsCount
     * @return ProductsCollection
     */
    public static function createProductsCollection($productsCount = 1, $variantsCount = 1)
    {

        $products = new ProductsCollection();

        for ($i = 0; $i < $productsCount; $i++) {

            $products->add(
                (new Product(
                    $i+1,
                    'simple product',
                    self::createProductVariants($variantsCount),
                    self::createCategoriesCollection()
                ))->setUrl('getresponse.com')
            );
        }

        return $products;
    }

    /**
     * @param int $count - number of variants in collection
     * @return VariantsCollection
     */
    public static function createProductVariants($count = 1)
    {
        $variants = new VariantsCollection();

        for ($i = 0; $i < $count; $i++) {
            $imageCollection = new ImagesCollection();
            $imageCollection->add(new Image('https://getresponse.com', 1));

            $productVariant = new Variant(
                $i+1,
                'simple product',
                9.99,
                12.00,
                'simple-product'
            );

            $productVariant
                ->setQuantity($i+1)
                ->setUrl('https://getresponse.com')
                ->setDescription('This is description')
                ->setImages($imageCollection);

            $variants->add($productVariant);
        }

        return $variants;
    }

    /**
     * @return AddOrderCommand
     */
    public static function createAddOrderCommand()
    {
        return new AddOrderCommand(
            self::createOrder(),
            'simple@example.com',
            'listId',
            'shopId'
        );
    }

    /**
     * @return Order
     */
    private static function createOrder()
    {
        return new Order(
            '21',
            self::createProductsCollection(),
            20.00,
            25.00,
            'http://getresponse.com',
            'PLN',
            'pending',
            '431',
            'This is description',
            3.53,
            'awaiting',
            '2018-05-17T16:15:33+0200',
            self::createAddress(),
            self::createAddress()
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

    /**
     * @param ExportSettings $exportSettings
     * @return ExportContactCommand
     */
    public static function createExportContactCommandWithSettings(ExportSettings $exportSettings)
    {
        $customFieldsCollection = new ContactCustomFieldsCollection();
        $customFieldsCollection->add(new ContactCustomField('id1', 'company', 'country'));

        return new ExportContactCommand(
            'adam.kowalski@getresponse.com',
            'Adam Kowalski',
            'origin',
            $exportSettings,
            $customFieldsCollection,
            self::createHistoricalOrderCollection()
        );
    }

    /**
     * @return HistoricalOrderCollection
     */
    private static function createHistoricalOrderCollection()
    {
        $order = new HistoricalOrder(
            '21',
            self::createProductsCollection(),
            20.00,
            25.00,
            'http://getresponse.com',
            'PLN',
            'pending',
            '431',
            'This is description',
            3.53,
            'awaiting',
            '2018-05-17T16:15:33+0200',
            self::createAddress(),
            self::createAddress(),
            new Cart('100001', self::createProductsCollection(), 'PLN', 10.00, 123.3)
        );

        $historicalOrderCollection = new HistoricalOrderCollection();
        $historicalOrderCollection->add($order);

        return $historicalOrderCollection;
    }

    /**
     * @param string $name
     * @return AddContactCommand
     */
    public static function createAddContactCommand($name = 'Adam Kowalski')
    {
        $customFieldCollection = new ContactCustomFieldsCollection();
        $customFieldCollection->add(new ContactCustomField('id_1',  'value_1'));
        $customFieldCollection->add(new ContactCustomField('id_2', 'value_2'));

        return new AddContactCommand(
            'adam.kowalski@getresponse.com',
            $name,
            'contactListId',
            3,
            $customFieldCollection,
            'origin'
        );
    }

    /**
     * @return ProductMapping
     */
    public static function createEmptyProductMapping()
    {
        return new ProductMapping(null, null, null, null, null);
    }

}
