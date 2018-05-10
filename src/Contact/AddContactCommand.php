<?php
namespace GrShareCode\Contact;

/**
 * Class AddContactCommand
 * @package GrShareCode\Contact
 */
class AddContactCommand
{
    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var string */
    private $listId;

    /** @var int */
    private $dayOfCycle;

    /** @var CustomFieldsCollection */
    private $customFieldsCollection;

    /**
     * @param string $name
     * @param string $email
     * @param string $listId
     * @param int $dayOfCycle
     * @param CustomFieldsCollection $customFieldsCollection
     */
    public function __construct($name, $email, $listId, $dayOfCycle, $customFieldsCollection)
    {
        $this->name = $name;
        $this->email = $email;
        $this->listId = $listId;
        $this->dayOfCycle = $dayOfCycle;
        $this->customFieldsCollection = $customFieldsCollection;
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
    public function getListId()
    {
        return $this->listId;
    }

    /**
     * @return int
     */
    public function getDayOfCycle()
    {
        return $this->dayOfCycle;
    }

    /**
     * @return CustomFieldsCollection
     */
    public function getCustomFieldsCollection()
    {
        return $this->customFieldsCollection;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
