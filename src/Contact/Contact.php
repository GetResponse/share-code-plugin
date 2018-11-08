<?php
namespace GrShareCode\Contact;

use GrShareCode\Contact\ContactCustomField\ContactCustomField;
use GrShareCode\Contact\ContactCustomField\ContactCustomFieldsCollection;

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
     * @param ContactCustomField $originCustomField
     * @return bool
     */
    public function hasOriginCustomField(ContactCustomField $originCustomField)
    {
        /** @var ContactCustomField $custom */
        foreach ($this->contactCustomFieldCollection as $custom) {
            if ($custom->getId() == $originCustomField->getId() && 1 == count($custom->getValue()) && 1 == count($originCustomField->getValue()) && $custom->getValue()[0] == $originCustomField->getValue()[0]) {
                return true;
            }
        }

        return false;
    }
}
