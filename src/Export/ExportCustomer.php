<?php
namespace GrShareCode\Export;

use GrShareCode\Cart\CartService;
use GrShareCode\Contact\ContactService;
use GrShareCode\DbRepositoryInterface;
use GrShareCode\Export\Settings\ExportSettingsFactory;
use GrShareCode\GetresponseApi;
use GrShareCode\GetresponseApiException;
use GrShareCode\Order\OrderService;
use GrShareCode\Product\ProductService;

/**
 * Class ExportCustomer
 * @package GrShareCode\Export
 */
class ExportCustomer
{
    /** @var GetresponseApi */
    private $api;

    /** @var DbRepositoryInterface */
    private $dbRepository;

    /** @var array */
    private $exportSettingsParams;

    public function __construct(
        GetresponseApi $api,
        DbRepositoryInterface $dbRepository,
        array $exportSettingsParams
    ) {
        $this->api = $api;
        $this->dbRepository = $dbRepository;
        $this->exportSettingsParams = $exportSettingsParams;
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    public function execute(ExportContactCommand $exportContactCommand)
    {
        $productService = new ProductService($this->api, $this->dbRepository);

        $exportService = new ExportCustomersService(
            ExportSettingsFactory::createFromArray($this->exportSettingsParams),
            new ContactService($this->api),
            new CartService($this->api, $this->dbRepository, $productService),
            new OrderService($this->api, $this->dbRepository, $productService)
        );

        $exportService->exportContact($exportContactCommand);

    }
}