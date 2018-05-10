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

    /**
     * @param string $contactId
     * @param string $name
     * @param string $email
     */
    public function __construct($contactId, $name, $email)
    {
        $this->contactId = $contactId;
        $this->name = $name;
        $this->email = $email;
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
