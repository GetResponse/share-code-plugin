<?php
namespace GrShareCode\CustomField;

use GrShareCode\CustomField\CustomFieldFilter\CustomFieldFilterInterface;
use GrShareCode\TypedCollection;

/**
 * Class CustomFieldCollection
 * @package GrShareCode\CustomField
 */
class CustomFieldCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType(CustomField::class);
    }

    /**
     * @param array $customFields
     * @return CustomFieldCollection
     */
    public static function fromApiResponse(array $customFields)
    {
        $collection = new CustomFieldCollection();

        foreach ($customFields as $field) {

            $collection->add(
                new CustomField(
                    $field['customFieldId'],
                    $field['name'],
                    $field['fieldType'],
                    $field['valueType']
                )
            );
        }

        return $collection;
    }

    /**
     * @param CustomFieldFilterInterface $customFieldFilter
     * @return CustomFieldCollection
     */
    public function filterBy(CustomFieldFilterInterface $customFieldFilter)
    {
        $collection = new self();
        foreach ($this->getIterator() as $customField) {
            if ($customFieldFilter->isEligible($customField)) {
                $collection->add($customField);
            }
        }

        return $collection;
    }

}
