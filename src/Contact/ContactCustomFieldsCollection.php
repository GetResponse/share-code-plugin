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
        $clone = clone $this;
        $this->clear();

        /** @var ContactCustomField $ccf */
        foreach ($clone->getIterator() as $ccf) {
            if ($ccf->getId() !== $contactCustomField->getId()) {
                $this->add($ccf);
            }
        }
    }
}
