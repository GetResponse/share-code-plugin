<?php
namespace GrShareCode\Export;

use GrShareCode\Cache\CacheNull;
use GrShareCode\Cart\CartService;
use GrShareCode\Contact\ContactService;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Order\OrderService;
use GrShareCode\Product\ProductService;

/**
 * Class ExportContactServiceFactory
 * @package GrShareCode\Export
 */
class ExportContactServiceFactory
{
    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @return ExportContactService
     */
    public static function create(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository)
    {
        $productService = new ProductService($getresponseApiClient, $dbRepository);

        return new ExportContactService(
            new ContactService($getresponseApiClient),
            new CartService($getresponseApiClient, $dbRepository, $productService, new CacheNull()),
            new OrderService($getresponseApiClient, $dbRepository, $productService)
        );
    }
}