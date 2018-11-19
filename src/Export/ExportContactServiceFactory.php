<?php
namespace GrShareCode\Export;

use GrShareCode\Contact\ContactServiceFactory;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\Api\GetresponseApiClient;
use GrShareCode\Order\OrderServiceFactory;

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
    public function create(GetresponseApiClient $getresponseApiClient, DbRepositoryInterface $dbRepository, $originValue)
    {
        return new ExportContactService(
            (new ContactServiceFactory())->create(
                $getresponseApiClient,
                $dbRepository,
                $originValue
            ),
            (new OrderServiceFactory())->create(
                $getresponseApiClient,
                $dbRepository
            )
        );
    }
}