<?php
namespace GrShareCode\Contact;

use GrShareCode\Validation\Assert\Assert;
use GrShareCode\Validation\Assert\InvalidArgumentException;

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

    /** @var ContactCustomFieldsCollection */
    private $customFieldsCollection;

    /** @var string */
    private $originValue;

    /**
     * @param string $email
     * @param string $name
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param ContactCustomFieldsCollection $customFieldsCollection
     * @param string $originValue
     * @throws InvalidArgumentException
     */
    public function __construct($email, $name, $contactListId, $dayOfCycle, $customFieldsCollection, $originValue)
    {
        Assert::that($email, 'Email in ' . AddContactCommand::class . ' should be a not null string')
            ->notNull()
            ->notEmpty()
            ->string();

        Assert::that($contactListId, 'Contact list in ' . AddContactCommand::class . ' should be a not null string')
            ->notNull()
            ->notEmpty()
            ->string();

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
     * @return ContactCustomFieldsCollection
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
     * @param ContactCustomField $customField
     */
    public function addCustomField(ContactCustomField $customField)
    {
        $this->customFieldsCollection->add($customField);
    }
}
