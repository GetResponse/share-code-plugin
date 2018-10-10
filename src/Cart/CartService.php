<?php
namespace GrShareCode\Cart;

use GrShareCode\Cache\CacheInterface;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\GetresponseApiException;
use GrShareCode\Product\ProductService;

/**
 * Class CartService
 * @package GrShareCode\Cart
 */
class CartService
{
    const CACHE_TTL = 600;
    const CART_KEY = 'cart_key';

    /** @var GetresponseApiClient */
    private $getresponseApiClient;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /** @var ProductService */
    private $productService;

    /** @var CacheInterface */
    private $cache;

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @param ProductService $productService
     * @param CacheInterface $cache
     */
    public function __construct(
        GetresponseApiClient $getresponseApiClient,
        DbRepositoryInterface $dbRepository,
        ProductService $productService,
        CacheInterface $cache
    ) {
        $this->getresponseApiClient = $getresponseApiClient;
        $this->dbRepository = $dbRepository;
        $this->productService = $productService;
        $this->cache = $cache;
    }

    /**
     * @param AddCartCommand $addCartCommand
     * @throws GetresponseApiException
     */
    public function exportCart(AddCartCommand $addCartCommand)
    {
        $contact = $this->getresponseApiClient->getContactByEmail(
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
            $grCartId = $this->getresponseApiClient->createCart($grShopId, $createCartPayload);
            $this->dbRepository->saveCartMapping($grShopId, $externalCartId, $grCartId);
        }
    }

    /**
     * @param AddCartCommand $addCartCommand
     * @throws GetresponseApiException
     */
    public function sendCart(AddCartCommand $addCartCommand)
    {
        if ($this->cartAlreadySent($addCartCommand->getCart())) {
            return;
        }

        $contact = $this->getContact($addCartCommand->getEmail(), $addCartCommand->getContactListId());

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

        $this->cache->set($this->getCartCacheKey($cart), $this->getCartHash($cart), self::CACHE_TTL);
    }

    /**
     * @param Cart $cart
     * @param $shopId
     * @param $contactId
     * @return array
     * @throws GetresponseApiException
     */
    private function getPayloadFromCart(Cart $cart, $shopId, $contactId)
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

            $grCartId = $this->getresponseApiClient->createCart($grShopId, $createCartPayload);
            $this->dbRepository->saveCartMapping($grShopId, $externalCartId, $grCartId);

        } else {

            if (empty($createCartPayload['selectedVariants'])) {
                $this->dbRepository->removeCartMapping($grShopId, $externalCartId, $grCartId);
                $this->getresponseApiClient->removeCart($grShopId, $grCartId);
                return;
            }

            $this->getresponseApiClient->updateCart($grShopId, $grCartId, $createCartPayload);
        }
    }

    /**
     * @param string $email
     * @param string $contactListId
     * @return array|string
     * @throws GetresponseApiException
     */
    private function getContact($email, $contactListId)
    {
        $userCacheKey = $email . '_' . $contactListId;

        if ($contact = $this->cache->get($userCacheKey)) {
            $contact = json_decode($contact, true);

        } else {
            $contact = $this->getresponseApiClient->getContactByEmail($email, $contactListId);
            $this->cache->set($userCacheKey, json_encode($contact), self::CACHE_TTL);
        }

        return $contact;
    }

    /**
     * @param Cart $cart
     * @return string
     */
    private function getCartCacheKey(Cart $cart)
    {
        return self::CART_KEY . $cart->getCartId();
    }

    /**
     * @param Cart $cart
     * @return string
     */
    private function getCartHash(Cart $cart)
    {
        return md5(serialize($cart->getProducts()));
    }

    /**
     * @param Cart $cart
     * @return bool
     */
    private function cartAlreadySent(Cart $cart)
    {
        return $this->getCartHash($cart) === $this->cache->get($this->getCartCacheKey($cart));
    }

}
