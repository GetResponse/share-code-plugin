<?php
namespace GrShareCode\Cart;

use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Cache\CacheInterface;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\Product\ProductServiceFactory;

/**
 * Class CartServiceFactory
 * @package GrShareCode\Cart
 */
class CartServiceFactory
{

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @param CacheInterface $cache
     * @return CartService
     */
    public function create(
        GetresponseApiClient $getresponseApiClient,
        DbRepositoryInterface $dbRepository,
        CacheInterface $cache
    ) {
        return new CartService(
            $getresponseApiClient,
            $dbRepository,
            (new ProductServiceFactory())->create($getresponseApiClient, $dbRepository),
            $cache
        );
    }
}