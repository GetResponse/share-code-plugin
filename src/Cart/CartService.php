<?php
namespace GrShareCode\Cart;

use GrShareCode\Cache\CacheInterface;
use GrShareCode\Cart\Command\AddCartCommand;
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
    public function sendCart(AddCartCommand $addCartCommand)
    {
        if (!$this->hasCartPayloadChangedSinceLastRequest($addCartCommand->getCart())) {
            return;
        }

        $cart = $addCartCommand->getCart();
        $grCartId = $this->dbRepository->getGrCartIdFromMapping($addCartCommand->getShopId(), $cart->getCartId());

        if (empty($grCartId)) {
            $this->addCart($addCartCommand);
        } else {
            $this->updateCart($addCartCommand, $grCartId);
        }
    }

    /**
     * @param AddCartCommand $addCartCommand
     * @throws GetresponseApiException
     */
    private function addCart(AddCartCommand $addCartCommand)
    {
        if (0 === $addCartCommand->getCart()->getProducts()->count()) {
            return;
        }

        $contact = $this->getContact($addCartCommand->getEmail(), $addCartCommand->getContactListId());

        if (empty($contact)) {
            return;
        }

        $cartPayload = $this->getPayloadFromCart(
            $addCartCommand->getCart(),
            $addCartCommand->getShopId()
        );
        $cartPayload['contactId'] = $contact['contactId'];

        if (empty($cartPayload['selectedVariants'])) {
            return;
        }

        $grCartId = $this->getresponseApiClient->createCart(
            $addCartCommand->getShopId(),
            $cartPayload
        );

        $this->dbRepository->saveCartMapping(
            $addCartCommand->getShopId(),
            $addCartCommand->getCart()->getCartId(),
            $grCartId
        );

        $this->cache->set(
            $this->getCartCacheKey($addCartCommand->getCart()),
            $this->getCartHash($addCartCommand->getCart()),
            self::CACHE_TTL
        );
    }

    /**
     * @param AddCartCommand $addCartCommand
     * @param $grCartId
     * @throws GetresponseApiException
     */
    private function updateCart(AddCartCommand $addCartCommand, $grCartId)
    {
        $cartPayload = $this->getPayloadFromCart(
            $addCartCommand->getCart(),
            $addCartCommand->getShopId()
        );

        if (empty($cartPayload['selectedVariants'])) {
            $this->dbRepository->removeCartMapping($addCartCommand->getShopId(), $addCartCommand->getCart()->getCartId(), $grCartId);
            $this->getresponseApiClient->removeCart($addCartCommand->getShopId(), $grCartId);
            return;
        }

        $this->getresponseApiClient->updateCart($addCartCommand->getShopId(), $grCartId, $cartPayload);

        $this->cache->set(
            $this->getCartCacheKey($addCartCommand->getCart()),
            $this->getCartHash($addCartCommand->getCart()),
            self::CACHE_TTL
        );
    }

    /**
     * @param Cart $cart
     * @param $shopId
     * @return array
     * @throws GetresponseApiException
     */
    private function getPayloadFromCart(Cart $cart, $shopId)
    {
        $variants = $this->productService->getProductsVariants($cart->getProducts(), $shopId);

        return [
            'currency' => $cart->getCurrency(),
            'totalPrice' => $cart->getTotalPrice(),
            'selectedVariants' => $variants,
            'externalId' => $cart->getCartId(),
            'totalTaxPrice' => $cart->getTotalTaxPrice(),
        ];
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
            $contact = $this->getresponseApiClient->findContactByEmailAndListId($email, $contactListId);
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
    private function hasCartPayloadChangedSinceLastRequest(Cart $cart)
    {
        return $this->getCartHash($cart) !== $this->cache->get($this->getCartCacheKey($cart));
    }

}
