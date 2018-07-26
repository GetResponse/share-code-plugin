<?php
namespace GrShareCode\Cart;

use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\ProductService;

/**
 * Class CartService
 * @package GrShareCode\Cart
 */
class CartService
{
    /** @var GetresponseApi */
    private $getresponseApi;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /** @var ProductService */
    private $productService;

    /**
     * @param GetresponseApi $getresponseApi
     * @param DbRepositoryInterface $dbRepository
     * @param ProductService $productService
     */
    public function __construct(
        GetresponseApi $getresponseApi,
        DbRepositoryInterface $dbRepository,
        ProductService $productService
    ) {
        $this->getresponseApi = $getresponseApi;
        $this->dbRepository = $dbRepository;
        $this->productService = $productService;
    }

    /**
     * @param AddCartCommand $addCartCommand
     * @throws GetresponseApiException
     */
    public function exportCart(AddCartCommand $addCartCommand)
    {
        $contact = $this->getresponseApi->getContactByEmail(
            $addCartCommand->getEmail(),
            $addCartCommand->getContactListId()
        );

        if (empty($contact)) {
            return;
        }

        $grShopId = $addCartCommand->getShopId();
        $cart = $addCartCommand->getCart();
        $externalCartId = $cart->getCartId();

        $createCartPayload = $this->getPayloadFromCart($cart, $grShopId, $contact['contactId']);

        $grCartId = $this->dbRepository->getGrCartIdFromMapping($grShopId, $externalCartId);

        if (empty($grCartId)) {
            $grCartId = $this->getresponseApi->createCart($grShopId, $createCartPayload);
            $this->dbRepository->saveCartMapping($grShopId, $externalCartId, $grCartId);
        }
    }

    /**
     * @param AddCartCommand $addCartCommand
     * @throws GetresponseApiException
     */
    public function sendCart(AddCartCommand $addCartCommand)
    {
        $contact = $this->getresponseApi->getContactByEmail(
            $addCartCommand->getEmail(),
            $addCartCommand->getContactListId()
        );

        if (empty($contact)) {
            return;
        }

        $cart = $addCartCommand->getCart();

        $createCartPayload = $this->getPayloadFromCart($cart, $addCartCommand->getShopId(), $contact['contactId']);

        $this->upsertCartToGr(
            $addCartCommand->getShopId(),
            $cart->getCartId(),
            $createCartPayload
        );
    }

    /**
     * @param $cart
     * @param $shopId
     * @param $contactId
     * @return array
     * @throws GetresponseApiException
     */
    private function getPayloadFromCart($cart, $shopId, $contactId)
    {
        $variants = $this->productService->getProductsVariants($cart->getProducts(), $shopId);

        return [
            'contactId' => $contactId,
            'currency' => $cart->getCurrency(),
            'totalPrice' => $cart->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId' => $cart->getCartId(),
            'totalTaxPrice' => $cart->getTotalTaxPrice(),
        ];
    }

    /**
     * @param string $grShopId
     * @param int $externalCartId
     * @param array $createCartPayload
     * @throws GetresponseApiException
     */
    private function upsertCartToGr($grShopId, $externalCartId, $createCartPayload)
    {
        $grCartId = $this->dbRepository->getGrCartIdFromMapping($grShopId, $externalCartId);

        if (empty($grCartId)) {

            if (empty($createCartPayload['selectedVariants'])) {
                return;
            }

            $grCartId = $this->getresponseApi->createCart($grShopId, $createCartPayload);
            $this->dbRepository->saveCartMapping($grShopId, $externalCartId, $grCartId);

        } else {

            if (empty($createCartPayload['selectedVariants'])) {
                $this->dbRepository->removeCartMapping($grShopId, $externalCartId, $grCartId);
                $this->getresponseApi->removeCart($grShopId, $grCartId);
                return;
            }

            $this->getresponseApi->updateCart($grShopId, $grCartId, $createCartPayload);
        }
    }
}
