<?php
namespace GrShareCode\Export;

use GrShareCode\Contact\ContactCustomFieldCollectionFactory;
use GrShareCode\Contact\ContactFactory;
use GrShareCode\Contact\ContactPayloadFactory;
use GrShareCode\Contact\ContactService;
use GrShareCode\CustomField\CustomFieldService;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\GetresponseApiClient;
use GrShareCode\Order\OrderPayloadFactory;
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
     * @param $originValue
     * @return ExportContactService
     */
    public static function create(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository, $originValue)
    {
        return new ExportContactService(
            new ContactService(
                $getresponseApiClient,
                new ContactPayloadFactory(),
                new ContactFactory(new ContactCustomFieldCollectionFactory()),
                new CustomFieldService($getresponseApiClient),
                $dbRepository,
                $originValue
            ),
            new OrderService(
                $getresponseApiClient,
                $dbRepository,
                new ProductService($getresponseApiClient, $dbRepository),
                new OrderPayloadFactory()
            )
        );
    }
}