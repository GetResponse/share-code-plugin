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
    private $contactListId;

    /** @var int */
    private $dayOfCycle;

    /** @var CustomFieldsCollection */
    private $customFieldsCollection;

    /** @var string */
    private $originValue;

    /**
     * @param string $email
     * @param string $name
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param CustomFieldsCollection $customFieldsCollection
     * @param string $originValue
     */
    public function __construct($email, $name, $contactListId, $dayOfCycle, $customFieldsCollection, $originValue)
    {
        $this->email = $email;
        $this->name = $name;
        $this->contactListId = $contactListId;
        $this->dayOfCycle = $dayOfCycle;
        $this->customFieldsCollection = $customFieldsCollection;
        $this->originValue = $originValue;
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
    public function getContactListId()
    {
        return $this->contactListId;
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

    /**
     * @return string
     */
    public function getOriginValue()
    {
        return $this->originValue;
    }

    /**
     * @param CustomField $customField
     */
    public function addCustomField(CustomField $customField)
    {
        $this->customFieldsCollection->add($customField);
    }
}
