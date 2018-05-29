<?php
namespace GrShareCode\Export;

use GrShareCode\Cart\AddCartCommand;
use GrShareCode\Cart\CartFactory;
use GrShareCode\Cart\CartService;
use GrShareCode\Contact\AddContactCommand;
use GrShareCode\Contact\ContactNotFoundException;
use GrShareCode\Contact\ContactService;
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

    /** @var CartService */
    private $cartService;

    /** @var OrderService */
    private $orderService;

    /**
     * @param ContactService $contactService
     * @param CartService $cartService
     * @param OrderService $orderService
     */
    public function __construct(ContactService $contactService, CartService $cartService, OrderService $orderService)
    {
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
        $this->exportCustomer($exportContactCommand);
        $this->sendEcommerceData($exportContactCommand);
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function exportCustomer(ExportContactCommand $exportContactCommand)
    {
        $exportSettings = $exportContactCommand->getExportSettings();

        try {
            $contact = $this->contactService->getContactByEmail(
                $exportContactCommand->getEmail(),
                $exportSettings->getContactListId()
            );

            $this->contactService->updateContactOnExport($exportContactCommand, $contact->getContactId());

        } catch (ContactNotFoundException $e) {

            $addContactCommand = new AddContactCommand(
                $exportContactCommand->getEmail(),
                $exportContactCommand->getName(),
                $exportSettings->getContactListId(),
                $exportSettings->getDayOfCycle(),
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
        $exportSettings = $exportContactCommand->getExportSettings();

        if (!$exportSettings->getEcommerceConfig()->isEcommerceEnabled()) {
            return;
        }

        foreach ($exportContactCommand->getHistoricalOrderCollection() as $historicalOrder) {

            $shopId = $exportSettings->getEcommerceConfig()->getShopId();

            $addCartCommand = new AddCartCommand(
                CartFactory::createFromHistoricalOrder($historicalOrder),
                $exportContactCommand->getEmail(),
                $exportSettings->getContactListId(),
                $shopId
            );
            $this->cartService->sendCart($addCartCommand);

            $addOrderCommand = new AddOrderCommand(
                $historicalOrder,
                $exportContactCommand->getEmail(),
                $exportSettings->getContactListId(),
                $shopId
            );
            $addOrderCommand->setToSkipAutomation();

            $this->orderService->sendOrder($addOrderCommand);
        }
    }
}