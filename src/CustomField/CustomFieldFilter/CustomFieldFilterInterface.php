<?php
namespace GrShareCode\CustomField\CustomFieldFilter;

use GrShareCode\CustomField\CustomField;

interface CustomFieldFilterInterface
{
    /**
     * @param CustomField $customField
     * @return bool
     */
    public function isEligible(CustomField $customField);
}