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

    /** @var string */
    private $originValue;

    /** @var ExportSettings */
    private $exportSettings;

    /** @var CustomFieldsCollection */
    private $customFieldsCollection;

    /** @var HistoricalOrderCollection */
    private $historicalOrderCollection;

    /**
     * @param string $email
     * @param string $name
     * @param string $originValue
     * @param ExportSettings $exportSettings
     * @param CustomFieldsCollection $customFieldsCollection
     * @param HistoricalOrderCollection $historicalOrderCollection
     */
    public function __construct(
        $email,
        $name,
        $originValue,
        ExportSettings $exportSettings,
        CustomFieldsCollection $customFieldsCollection,
        HistoricalOrderCollection $historicalOrderCollection
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->originValue = $originValue;
        $this->exportSettings = $exportSettings;
        $this->customFieldsCollection = $customFieldsCollection;
        $this->historicalOrderCollection = $historicalOrderCollection;
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
     * @return string
     */
    public function getOriginValue()
    {
        return $this->originValue;
    }

    /**
     * @return ExportSettings
     */
    public function getExportSettings()
    {
        return $this->exportSettings;
    }

    /**
     * @return CustomFieldsCollection
     */
    public function getCustomFieldsCollection()
    {
        return $this->customFieldsCollection;
    }

    /**
     * @return HistoricalOrderCollection
     */
    public function getHistoricalOrderCollection()
    {
        return $this->historicalOrderCollection;
    }
}
