<?php
namespace GrShareCode\Export;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\CartService;
use GrShareCode\Contact\AddContactCommand;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
use GrShareCode\Export\Settings\ExportSettings;
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

    /** @var ExportSettings */
    private $exportSettings;

    /** @var CartService */
    private $cartService;

    /** @var OrderService */
    private $orderService;

    /**
     * @param ExportSettings $exportSettings
     * @param ContactService $contactService
     * @param CartService $cartService
     * @param OrderService $orderService
     */
    public function __construct(
        ExportSettings $exportSettings,
        ContactService $contactService,
        CartService $cartService,
        OrderService $orderService
    ) {
        $this->exportSettings = $exportSettings;
        $this->contactService = $contactService;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    public function exportContact(ExportContactCommand $exportContactCommand)
    {
        $this->exportCustomer($this->exportSettings, $exportContactCommand);
        $this->sendEcommerceData($exportContactCommand);
    }

    /**
     * @param ExportSettings $config
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function exportCustomer(ExportSettings $config, ExportContactCommand $exportContactCommand)
    {
        try {
            $contact = $this->contactService->getContactByEmail(
                $exportContactCommand->getEmail(),
                $config->getContactListId()
            );

            $this->contactService->updateContactOnExport($config, $exportContactCommand, $contact->getContactId());

        } catch (ContactNotFoundException $e) {

            $addContactCommand = new AddContactCommand(
                $exportContactCommand->getEmail(),
                $exportContactCommand->getName(),
                $config->getContactListId(),
                $config->getDayOfCycle(),
                $exportContactCommand->getCustomFieldsCollection()
            );

            $this->contactService->createContact($addContactCommand);
        }

    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function sendEcommerceData(ExportContactCommand $exportContactCommand)
    {
        if (!$this->exportSettings->getEcommerceConfig()->isEcommerceEnabled()) {
            return;
        }

        $shopId = $this->exportSettings->getEcommerceConfig()->getShopId();

        $addCartCommand = new AddCartCommand(
            $exportContactCommand->getCart(),
            $exportContactCommand->getEmail(),
            $this->exportSettings->getContactListId(),
            $shopId
        );
        $this->cartService->sendCart($addCartCommand);

        $addOrderCommand = new AddOrderCommand(
            $exportContactCommand->getOrder(),
            $exportContactCommand->getEmail(),
            $this->exportSettings->getContactListId(),
            $shopId
        );
        $addOrderCommand->setToSkipAutomation();

        $this->orderService->sendOrder($addOrderCommand);
    }
}