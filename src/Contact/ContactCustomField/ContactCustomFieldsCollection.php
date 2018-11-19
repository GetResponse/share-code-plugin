<?php
namespace GrShareCode\Contact\ContactCustomField;

use GrShareCode\TypedCollection;

/**
 * Class ContactCustomFieldsCollection
 * @package GrShareCode\Contact\ContactCustomField
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
