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

    /**
     * @param ContactCustomField $contactCustomField
     * @return bool
     */
    public function hasContactCustomField(ContactCustomField $contactCustomField)
    {
        /** @var ContactCustomField $custom */
        foreach ($this->contactCustomFieldCollection as $custom) {
            if ($custom->getId() == $contactCustomField->getId() && 1 == count($custom->getValue()) && 1 == count($contactCustomField->getValue()) && $custom->getValue()[0] == $contactCustomField->getValue()[0]) {
                return true;
            }
        }

        return false;
    }
}
