<?php
namespace GrShareCode\Contact\ContactCustomField;

/**
 * Class ContactCustomFieldBuilder
 * @package GrShareCode\Contact\ContactCustomField
 */
class ContactCustomFieldBuilder
{
    /** @var ContactCustomFieldsCollection */
    private $contactCustomFieldCollection;

    /** @var ContactCustomFieldsCollection */
    private $newContactCustomFieldCollection;

    /**
     * @param ContactCustomFieldsCollection $contactCustomFieldCollection
     * @param ContactCustomFieldsCollection $newContactCustomFieldCollection
     */
    public function __construct(
        ContactCustomFieldsCollection $contactCustomFieldCollection,
        ContactCustomFieldsCollection $newContactCustomFieldCollection
    ) {
        $this->contactCustomFieldCollection = $contactCustomFieldCollection;
        $this->newContactCustomFieldCollection = $newContactCustomFieldCollection;
    }

    /**
     * @return ContactCustomFieldsCollection
     */
    public function getMergedCustomFieldsCollection()
    {
        $contactFieldCollection = new ContactCustomFieldsCollection();

        $customFields = $this->collectionToArray($this->contactCustomFieldCollection);
        $newCustomFields = $this->collectionToArray($this->newContactCustomFieldCollection);

        $customFieldIds = array_keys(array_merge($customFields, $newCustomFields));

        foreach ($customFieldIds as $customFieldId) {

            $contactCustomFieldValues = [];

            if (array_key_exists($customFieldId, $customFields)) {
                $customField = $customFields[$customFieldId];
                $contactCustomFieldValues = $customField->getValues();
            }

            if (array_key_exists($customFieldId, $newCustomFields)) {
                $newCustomField = $newCustomFields[$customFieldId];
                $contactCustomFieldValues = array_unique(array_merge($contactCustomFieldValues,
                    $newCustomField->getValues()));
            }

            $contactFieldCollection->add(
                new ContactCustomField($customFieldId, $contactCustomFieldValues)
            );
        }

        return $contactFieldCollection;
    }

    /**
     * @param ContactCustomFieldsCollection $contactCustomFieldCollection
     * @return array
     */
    private function collectionToArray(ContactCustomFieldsCollection $contactCustomFieldCollection)
    {
        $customFields = [];

        /** @var ContactCustomField $contactCustomField */
        foreach ($contactCustomFieldCollection as $contactCustomField) {
            $customFields[$contactCustomField->getId()] = $contactCustomField;
        }

        return $customFields;
    }

}