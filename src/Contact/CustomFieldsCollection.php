<?php
namespace GrShareCode\Contact;

use GrShareCode\TypedCollection;

/**
 * Class CustomFieldsCollection
 * @package GrShareCode\Contact
 */
class CustomFieldsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Contact\CustomField');
    }
}
