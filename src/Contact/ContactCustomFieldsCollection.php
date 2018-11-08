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
        $this->setItemType(ContactCustomField::class);
    }

    /**
     * @param ContactCustomField $contactCustomField
     */
    public function remove(ContactCustomField $contactCustomField)
    {
        $clone = $this->filter(new ContactCustomFieldRemoveFilter($contactCustomField));
        $this->clear();

        /** @var ContactCustomField $ccf */
        foreach ($clone->getIterator() as $ccf) {
            $this->add($ccf);
        }
    }
}
