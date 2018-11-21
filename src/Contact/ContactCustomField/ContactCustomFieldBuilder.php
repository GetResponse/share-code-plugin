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
     * Assumption: All custom field is TextType and StringValue
     * @return ContactCustomFieldsCollection
     */
    public function getMergedCustomFieldsCollection()
    {
        $contactFieldCollection = new ContactCustomFieldsCollection();

        $currentCustomFields = $this->collectionToArray($this->contactCustomFieldCollection);
        $newCustomFields = $this->collectionToArray($this->newContactCustomFieldCollection);

        foreach (array_merge($currentCustomFields, $newCustomFields) as $customField) {
            $contactFieldCollection->add($customField);
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