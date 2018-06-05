<?php
namespace GrShareCode\Job;

/**
 * Class Job
 * @package GrShareCode\Job
 */
class Job
{
    const NAME_EXPORT_CONTACT = 'export_contact';

    /** @var string */
    private $name;

    /** @var string */
    private $messageContent;

    /**
     * @param string $name
     * @param string $messageContent
     */
    public function __construct($name, $messageContent)
    {
        $this->name = $name;
        $this->messageContent = $messageContent;
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
    public function getMessageContent()
    {
        return $this->messageContent;
    }


}