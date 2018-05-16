<?php
namespace GrShareCode\Cart\Validation;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Product\Category\Category;
use GrShareCode\Product\Product;

/**
 * Class Validator
 * @package GrShareCode\Cart
 */
class Validator
{
    /**
     * @param AddCartCommand $addCartCommand
     * @throws AddCartCommandException
     */
    public function validate(AddCartCommand $addCartCommand)
    {
        Assert::that($addCartCommand->getEmail())->email();
        Assert::that($addCartCommand->getContactListId())->notBlank()->string();
        Assert::that($addCartCommand->getShopId())->notBlank()->string();

        $cart = $addCartCommand->getCart();
        Assert::that($cart->getCartId())->notNull()->integer();
        Assert::that($cart->getCurrency())->notBlank()->string()->length(3);
        Assert::that($cart->getTotalPrice())->notNull()->float();
        Assert::that($cart->getTotalTaxPrice())->nullOr()->float();

        /** @var Product $product */
        foreach ($cart->getProducts() as $product) {

            Assert::that($product->getExternalId())->notNull()->integer();
            Assert::that($product->getName())->notBlank()->integer();
            Assert::that($product->getUrl())->nullOr()->string();
            Assert::that($product->getType())->nullOr()->string();
            Assert::that($product->getVendor())->nullOr()->string();

            /** @var Category $category */
            foreach ($product->getCategories() as $category) {
                Assert::that($category->getName())->notBlank()->string();
                Assert::that($category->getParentId())->nullOr()->string();
                Assert::that($category->getExternalId())->nullOr()->string();
                Assert::that($category->getUrl())->nullOr()->string();
            }

            $productVariant = $product->getVariant();




        }
    }




}