<?php
namespace GrShareCode\ContactList;

/**
 * Class Autoresponder
 * @package GrShareCode\ContactList
 */
class Autoresponder
{
    const ENABLED = 'enabled';

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $campaignId;

    /** @var string */
    private $subject;

    /** @var string */
    private $status;

    /** @var int */
    private $cycleDay;

    /**
     * @param string $id
     * @param string $name
     * @param string $campaignId
     * @param string $subject
     * @param string $status
     * @param int $cycleDay
     */
    public function __construct($id, $name, $campaignId, $subject, $status, $cycleDay)
    {
        $this->id = $id;
        $this->name = $name;
        $this->campaignId = $campaignId;
        $this->subject = $subject;
        $this->status = $status;
        $this->cycleDay = $cycleDay;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCycleDay()
    {
        return $this->cycleDay;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return self::ENABLED === $this->status;
    }
}
