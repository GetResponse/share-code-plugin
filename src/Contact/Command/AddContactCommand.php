<?php
namespace GrShareCode\Contact\Command;

use GrShareCode\Contact\ContactCustomFieldsCollection;
use GrShareCode\Validation\Assert\Assert;

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

    /** @var bool  */
    private $updateIfAlreadyExists;

    /**
     * @param string $email
     * @param string $name
     * @param string $contactListId
     * @param int $dayOfCycle
     * @param ContactCustomFieldsCollection $customFieldsCollection
     * @param bool $updateIfAlreadyExists
     */
    public function __construct($email, $name, $contactListId, $dayOfCycle, $customFieldsCollection, $updateIfAlreadyExists = false)
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
        $this->updateIfAlreadyExists = $updateIfAlreadyExists;
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
    public function getContactCustomFieldsCollection()
    {
        return $this->customFieldsCollection;
    }

    /**
     * @param ContactCustomFieldsCollection $customFieldsCollection
     */
    public function setCustomFieldsCollection(ContactCustomFieldsCollection $customFieldsCollection)
    {
        $this->customFieldsCollection = $customFieldsCollection;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function updateIfAlreadyExists()
    {
        return $this->updateIfAlreadyExists;
    }
}
