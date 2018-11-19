<?php
namespace GrShareCode\Order;

use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\Product\ProductServiceFactory;

/**
 * Class OrderServiceFactory
 * @package GrShareCode\Order
 */
class OrderServiceFactory
{

    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @return OrderService
     */
    public function create(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository)
    {
        return new OrderService(
            $getresponseApiClient,
            $dbRepository,
            (new ProductServiceFactory())->create($getresponseApiClient, $dbRepository),
            new OrderPayloadFactory()
        );
    }
}