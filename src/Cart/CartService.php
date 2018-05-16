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
    public function sendCart(AddCartCommand $addCartCommand)
    {
//        $this->validator->validate($addCartCommand);

        $contact = $this->getresponseApi->getContactByEmail($addCartCommand->getEmail(), $addCartCommand->getContactListId());

        if (empty($contact)) {
            return;
        }

        $cart = $addCartCommand->getCart();

        $variants = $this->productService->getProductVariants($cart->getProducts(), $addCartCommand->getShopId());

        $createCartPayload = [
            'contactId' => $contact['contactId'],
            'currency' => $cart->getCurrency(),
            'totalPrice' => $cart->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId' => $cart->getCartId(),
            'totalTaxPrice' => $cart->getTotalTaxPrice(),
        ];

        $this->upsertCartToGr($addCartCommand->getShopId(), $cart->getCartId(), $createCartPayload);
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
            $grCartId = $this->getresponseApi->createCart($grShopId, $createCartPayload);
            $this->dbRepository->saveCartMapping($grShopId, $externalCartId, $grCartId);
        } else {
            $this->getresponseApi->updateCart($grShopId, $grCartId, $createCartPayload);
        }
    }
}
