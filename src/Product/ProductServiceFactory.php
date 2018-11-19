<?php
namespace GrShareCode\Product;

use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\DbRepositoryInterface;

/**
 * Class ProductServiceFactory
 * @package GrShareCode\Product
 */
class ProductServiceFactory
{
    /**
     * @param GetresponseApiClient $getresponseApiClient
     * @param DbRepositoryInterface $dbRepository
     * @return ProductService
     */
    public function create(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository)
    {
        return new ProductService($getresponseApiClient, $dbRepository);
    }
}