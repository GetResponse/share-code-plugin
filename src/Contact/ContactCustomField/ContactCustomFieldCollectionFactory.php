<?php
namespace GrShareCode\Contact\ContactCustomField;

/**
 * Class ContactCustomFieldCollectionFactory
 * @package GrShareCode\Contact\ContactCustomField
 */
class ContactCustomFieldCollectionFactory
{
    /**
     * @param array $contact
     * @return ContactCustomFieldsCollection
     */
    public function fromApiResponse(array $contact)
    {
        $customFields = new ContactCustomFieldsCollection();

        if (!isset($contact['customFieldValues'])) {
            return $customFields;
        }

        foreach ($contact['customFieldValues'] as $customField) {

            $customFields->add(
                new ContactCustomField(
                    $customField['customFieldId'],
                    $customField['value']
                )
            );
        }

        return $customFields;
    }
}