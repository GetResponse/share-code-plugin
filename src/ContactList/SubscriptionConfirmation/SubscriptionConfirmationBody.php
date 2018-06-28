<?php
namespace GrShareCode\ContactList\SubscriptionConfirmation;

/**
 * Class SubscriptionConfirmationBody
 * @package GrShareCode\ContactList\SubscriptionConfirmation
 */
class SubscriptionConfirmationBody
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $contentPlain;

    /**
     * @param string $id
     * @param string $name
     * @param string $contentPlain
     */
    public function __construct($id, $name, $contentPlain)
    {
        $this->id = $id;
        $this->name = $name;
        $this->contentPlain = $contentPlain;
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
    public function getContentPlain()
    {
        return $this->contentPlain;
    }


}