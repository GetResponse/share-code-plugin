<?php
namespace GrShareCode\Contact;

/**
 * Class Contact
 * @package GrShareCode\Contact
 */
class Contact
{
    /** @var string */
    private $contactId;

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var ContactCustomFieldsCollection */
    private $contactCustomFieldCollection;

    /**
     * @param string $contactId
     * @param string $name
     * @param string $email
     * @param ContactCustomFieldsCollection $contactCustomFieldCollection
     */
    public function __construct($contactId, $name, $email, ContactCustomFieldsCollection $contactCustomFieldCollection)
    {
        $this->contactId = $contactId;
        $this->name = $name;
        $this->email = $email;
        $this->contactCustomFieldCollection = $contactCustomFieldCollection;
    }

    /**
     * @return ContactCustomFieldsCollection
     */
    public function getContactCustomFieldCollection()
    {
        return $this->contactCustomFieldCollection;
    }

    /**
     * @return string
     */
    public function getContactId()
    {
        return $this->contactId;
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
    public function getEmail()
    {
        return $this->email;
    }
}
