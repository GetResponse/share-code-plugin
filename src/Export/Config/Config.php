<?php
namespace GrShareCode\Export\Config;

/**
 * Class Config
 * @package GrShareCode\Export\Config
 */
class Config
{
    /** @var string */
    private $contactListId;

    /** @var null|int */
    private $dayOfCycle;

    /** @var bool */
    private $jobSchedulerEnabled;

    /** @var bool */
    private $updateContactEnabled;

    /** @var EcommerceConfig */
    private $ecommerceConfig;

    /**
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param bool $jobSchedulerEnabled
     * @param bool $updateContactEnabled
     * @param EcommerceConfig $ecommerceConfig
     */
    public function __construct(
        $contactListId,
        $dayOfCycle,
        $jobSchedulerEnabled,
        $updateContactEnabled,
        EcommerceConfig $ecommerceConfig
    ) {
        $this->contactListId = $contactListId;
        $this->dayOfCycle = $dayOfCycle;
        $this->jobSchedulerEnabled = $jobSchedulerEnabled;
        $this->updateContactEnabled = $updateContactEnabled;
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
     * @return bool
     */
    public function isJobSchedulerEnabled()
    {
        return $this->jobSchedulerEnabled;
    }

    /**
     * @return bool
     */
    public function isUpdateContactEnabled()
    {
        return $this->updateContactEnabled;
    }

    /**
     * @return EcommerceConfig
     */
    public function getEcommerceConfig()
    {
        return $this->ecommerceConfig;
    }

}