<?php
namespace GrShareCode\Export;

use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Export\Settings\ExportSettings;
use GrShareCode\Order\OrderCollection;

/**
 * Class ExportContactCommand
 * @package GrShareCode\Contact
 */
class ExportContactCommand
{
    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var ExportSettings */
    private $exportSettings;

    /** @var ContactCustomFieldsCollection */
    private $customFieldsCollection;

    /** @var OrderCollection */
    private $orderCollection;

    /**
     * @param string $email
     * @param string $name
     * @param ExportSettings $exportSettings
     * @param ContactCustomFieldsCollection $customFieldsCollection
     * @param OrderCollection $orderCollection
     */
    public function __construct(
        $email,
        $name,
        ExportSettings $exportSettings,
        ContactCustomFieldsCollection $customFieldsCollection,
        OrderCollection $orderCollection
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->exportSettings = $exportSettings;
        $this->customFieldsCollection = $customFieldsCollection;
        $this->orderCollection = $orderCollection;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ExportSettings
     */
    public function getExportSettings()
    {
        return $this->exportSettings;
    }

    /**
     * @return ContactCustomFieldsCollection
     */
    public function getCustomFieldsCollection()
    {
        return $this->customFieldsCollection;
    }

    /**
     * @return OrderCollection
     */
    public function getOrderCollection()
    {
        return $this->orderCollection;
    }
}
