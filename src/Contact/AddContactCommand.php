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
    private $listId;

    /** @var int */
    private $dayOfCycle;

    /** @var CustomFieldsCollection */
    private $customFieldsCollection;

    /**
     * @param string $name
     * @param string $listId
     * @param int $dayOfCycle
     * @param CustomFieldsCollection $customFieldsCollection
     */
    public function __construct($name, $listId, $dayOfCycle, $customFieldsCollection)
    {
        $this->name = $name;
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
}
