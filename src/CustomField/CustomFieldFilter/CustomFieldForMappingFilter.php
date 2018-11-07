<?php
namespace GrShareCode\CustomField\CustomFieldFilter;

use GrShareCode\CustomField\CustomField;

/**
 * Class TextFieldCustomFieldFilter
 * @package GrShareCode\CustomField\CustomFieldFilter
 */
class CustomFieldForMappingFilter implements CustomFieldFilterInterface
{
    const NAME_ORIGIN = 'origin';

    /**
     * @param CustomField $customField
     * @return bool
     */
    public function isEligible(CustomField $customField)
    {
        return $customField->getName() !== self::NAME_ORIGIN
            && $customField->isTextField();
    }
}