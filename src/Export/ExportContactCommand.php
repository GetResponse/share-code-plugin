<?php
namespace GrShareCode\Export;

use GrShareCode\Contact\CustomFieldsCollection;
use GrShareCode\Export\HistoricalOrder\HistoricalOrderCollection;
use GrShareCode\Export\Settings\ExportSettings;

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

    /** @var CustomFieldsCollection */
    private $customFieldsCollection;

    /** @var HistoricalOrderCollection */
    private $historicalOrderCollection;

    /**
     * @param string $email
     * @param string $name
     * @param ExportSettings $exportSettings
     * @param CustomFieldsCollection $customFieldsCollection
     * @param HistoricalOrderCollection $historicalOrderCollection
     */
    public function __construct(
        $email,
        $name,
        ExportSettings $exportSettings,
        CustomFieldsCollection $customFieldsCollection,
        HistoricalOrderCollection $historicalOrderCollection
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->exportSettings = $exportSettings;
        $this->customFieldsCollection = $customFieldsCollection;
        $this->historicalOrderCollection = $historicalOrderCollection;
    }

    /**
     * @return ExportSettings
     */
    public function getExportSettings()
    {
        return $this->exportSettings;
    }

    /**
     * @return HistoricalOrderCollection
     */
    public function getHistoricalOrderCollection()
    {
        return $this->historicalOrderCollection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return CustomFieldsCollection
     */
    public function getCustomFieldsCollection()
    {
        return $this->customFieldsCollection;
    }

}
