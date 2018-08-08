<?php
namespace GrShareCode\Contact;

use GrShareCode\TypedCollection;

/**
 * Class CustomFieldsCollection
 * @package GrShareCode\Contact
 */
class ContactCustomFieldsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Contact\ContactCustomField');
    }
}
