<?php
namespace GrShareCode\Export;

use GrShareCode\Export\Command\ExportContactCommand;
use GrShareCode\Contact\Command\AddContactCommand;
use GrShareCode\Contact\ContactService;
use GrShareCode\GetresponseApiException;
use GrShareCode\Order\Command\AddOrderCommand;
use GrShareCode\Order\Order;
use GrShareCode\Order\OrderService;

/**
 * Class ExportContactService
 * @package GrShareCode\Export
 */
class ExportContactService
{
    /** @var ContactService */
    private $contactService;

    /** @var OrderService */
    private $orderService;

    /**
     * @param ContactService $contactService
     * @param OrderService $orderService
     */
    public function __construct(ContactService $contactService, OrderService $orderService)
    {
        $this->contactService = $contactService;
        $this->orderService = $orderService;
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    public function exportContact(ExportContactCommand $exportContactCommand)
    {
        $this->exportCustomer($exportContactCommand);
        $this->exportEcommerceData($exportContactCommand);
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function exportCustomer(ExportContactCommand $exportContactCommand)
    {
        $addContactCommand = new AddContactCommand(
            $exportContactCommand->getEmail(),
            $exportContactCommand->getName(),
            $exportContactCommand->getExportSettings()->getContactListId(),
            $exportContactCommand->getExportSettings()->getDayOfCycle(),
            $exportContactCommand->getCustomFieldsCollection()
        );

        $this->contactService->addContact($addContactCommand);
    }

    /**
     * @param ExportContactCommand $exportContactCommand
     * @throws GetresponseApiException
     */
    private function exportEcommerceData(ExportContactCommand $exportContactCommand)
    {
        $exportSettings = $exportContactCommand->getExportSettings();

        if (!$exportSettings->getEcommerceConfig()->isEcommerceEnabled()) {
            return;
        }

        /** @var Order $order */
        foreach ($exportContactCommand->getOrderCollection() as $order) {

            $addOrderCommand = new AddOrderCommand(
                $order,
                $exportContactCommand->getEmail(),
                $exportSettings->getContactListId(),
                $exportSettings->getEcommerceConfig()->getShopId()
            );
            $addOrderCommand->setToSkipAutomation();

            $this->orderService->addOrder($addOrderCommand);
        }
    }
}