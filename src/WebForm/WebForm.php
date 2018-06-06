<?php
namespace GrShareCode\WebForm;

use GrShareCode\Validation\Assert\Assert;

/**
 * Class WebForm
 * @package GrShareCode\WebForm
 */
class WebForm
{
    const STATUS_DISABLED = 'disabled';
    const STATUS_ENABLED = 'enabled';

    /** @var string */
    private $webFormId;

    /** @var string */
    private $name;

    /** @var string */
    private $campaignName;

    /** @var string */
    private $status;

    /**
     * @param string $webFormId
     * @param string $name
     * @param string $campaignName
     * @param string $status
     */
    public function __construct($webFormId, $name, $campaignName, $status)
    {
        $this->webFormId = $webFormId;
        $this->name = $name;
        $this->campaignName = $campaignName;
        $this->setStatus($status);
    }

    /**
     * @param string $status
     */
    private function setStatus($status)
    {
        Assert::that($status)->choice([self::STATUS_DISABLED, self::STATUS_ENABLED]);
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getWebFormId()
    {
        return $this->webFormId;
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
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->status === self::STATUS_ENABLED;
    }
}