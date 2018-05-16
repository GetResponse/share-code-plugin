<?php
namespace GrShareCode\Export;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\CartService;
use GrShareCode\Contact\ContactService;
use GrShareCode\Contact\ExportContactCommand;
use GrShareCode\Export\Config\Config;
use GrShareCode\GetresponseApiException;
use GrShareCode\Order\AddOrderCommand;
use GrShareCode\Order\OrderService;

/**
 * Class ExportCustomersService
 * @package GrShareCode\Export
 */
class ExportCustomersService
{

    /** @var ContactService */
    private $contactService;

    /** @var Config */
    private $config;

    /** @var CartService */
    private $cartService;

    /** @var OrderService */
    private $orderService;

    /**
     * @param Config $config
     * @param ContactService $contactService
     * @param CartService $cartService
     * @param OrderService $orderService
     */
    public function __construct(
        Config $config,
        ContactService $contactService,
        CartService $cartService,
        OrderService $orderService
    ) {
        $this->contactService = $contactService;
        $this->config = $config;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    public function exportContact(ExportContactCommand $exportContactCommand)
    {
        $this->contactService->exportContact($this->config, $exportContactCommand);
        $this->sendEcommerceData($exportContactCommand);
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function sendEcommerceData(ExportContactCommand $exportContactCommand)
    {
        if ($this->config->getEcommerceConfig()->isEcommerceEnabled()) {

            $shopId = $this->config->getEcommerceConfig()->getShopId();

            $addCartCommand = new AddCartCommand(
                $exportContactCommand->getCart(),
                $exportContactCommand->getEmail(),
                $this->config->getContactListId(),
                $shopId
            );
            $this->cartService->sendCart($addCartCommand);

            $addOrderCommand = new AddOrderCommand(
                $exportContactCommand->getOrder(),
                $exportContactCommand->getEmail(),
                $this->config->getContactListId(),
                $shopId
            );
            $this->orderService->sendOrder($addOrderCommand);

        }
    }
}