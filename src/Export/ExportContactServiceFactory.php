<?php
namespace GrShareCode\Export;

use GrShareCode\Cart\CartService;
use GrShareCode\Contact\ContactService;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApi;
use GrShareCode\Order\OrderService;
use GrShareCode\Product\ProductService;

/**
 * Class ExportContactServiceFactory
 * @package GrShareCode\Export
 */
class ExportContactServiceFactory
{
    /**
     * @param GetresponseApi $api
     * @param DbRepositoryInterface $dbRepository
     * @return ExportContactService
     */
    public static function create(GetresponseApi $api, DbRepositoryInterface $dbRepository)
    {
        $productService = new ProductService($api, $dbRepository);

        return new ExportContactService(
            new ContactService($api),
            new CartService($api, $dbRepository, $productService),
            new OrderService($api, $dbRepository, $productService)
        );
    }
}