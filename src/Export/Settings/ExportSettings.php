<?php
namespace GrShareCode\Export\Settings;

/**
 * Class Config
 * @package GrShareCode\Export\Config
 */
class ExportSettings
{
    /** @var string */
    private $contactListId;
    /** @var null|int */
    private $dayOfCycle;
    /** @var EcommerceSettings */
    private $ecommerceConfig;

    /**
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param EcommerceSettings $ecommerceConfig
     */
    public function __construct(
        $contactListId,
        $dayOfCycle,
        EcommerceSettings $ecommerceConfig
    ) {
        $this->contactListId = $contactListId;
        $this->dayOfCycle = $dayOfCycle;
        $this->ecommerceConfig = $ecommerceConfig;
    }

    /**
     * @return string
     */
    public function getContactListId()
    {
        return $this->contactListId;
    }

    /**
     * @return null|int
     */
    public function getDayOfCycle()
    {
        return $this->dayOfCycle;
    }

    /**
     * @return EcommerceSettings
     */
    public function getEcommerceConfig()
    {
        return $this->ecommerceConfig;
    }

}