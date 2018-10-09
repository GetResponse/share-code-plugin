<?php
namespace GrShareCode\Contact;

/**
 * Class ContactCustomFieldCollectionFactory
 * @package GrShareCode\Contact
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

        foreach ($contact['customFieldValues'] as $customField) {
            $customFields->add(
                new ContactCustomField(
                    $customField['customFieldId'],
                    $customField['value'][0]
                )
            );
        }

        return $customFields;
    }
}